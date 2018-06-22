<?php

namespace NFePHP\eFinanc\Common;

/**
 * Crypto class Performs message encryption processes
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
class Crypto
{
    /**
     * @var string
     */
    protected $cipher;
    /**
     * @var string
     */
    protected $key;
    /**
     * @var string
     */
    protected $iv;
    /**
     * @var string
     */
    protected $certificate;
    /**
     * @var string
     */
    protected $fingerprint;
    /**
     * @var string
     */
    protected $keyencrypted;
    /**
     * constants
     */
    const AES_128_CBC = 'aes-128-cbc';
    const AES_128_CBF = 'aes-128-cfb';
    const AES_128_CBF1 = 'aes-128-cfb1';
    const AES_128_CBF8 = 'aes-128-cfb8';
    const AES_128_OFB = 'aes-128-ofb';
    const AES_192_CBC = 'aes-192-cbc';
    const AES_192_CBF = 'aes-192-cbf';
    const AES_192_CBF1 = 'aes-192-cbf1';
    const AES_192_CBF8 = 'aes-192-cbf8';
    const AES_192_OFB = 'aes-192-ofb';
    const AES_256_CBC = 'aes-256-cbc';
    const AES_256_CBF = 'aes-256-cbf';
    const AES_256_CBF1 = 'aes-256-cbf1';
    const AES_256_CBF8 = 'aes-256-cbf8';
    const AES_256_OFB = 'aes-256-ofb';
    
    /**
     * Constructor
     * Recive cer content and convert to pem, get certificate fingerprint and
     * creates a encryption key and initialization vector
     * @param string $derdata certificate content in DER format (usual)
     * @param string $cipher encoded cipher
     */
    public function __construct($derdata, $cipher = self::AES_128_CBC)
    {
        $this->cipher = $cipher;
        $this->certificate = $this->convertDERtoPEM($derdata);
        $this->fingerprint = $this->thumbprint($this->certificate);
        $this->keyGenerate();
    }
    
    /**
     * Generate a key and initialization vector
     * and encrypt this key with a certificate
     * and combine with initialization vector in base64 format
     * @return void
     */
    public function keyGenerate()
    {
        $length = openssl_cipher_iv_length($this->cipher);
        $this->key = openssl_random_pseudo_bytes($length);
        $this->iv = openssl_random_pseudo_bytes($length);
        $this->keyencrypted = $this->encryptkey($this->key, $this->iv);
    }
    
    /**
     * Recover encryped key+iv in base64
     * @return string
     */
    public function getEncrypedKey():string
    {
        return $this->keyencrypted;
    }
    
    /**
     * Recover Thumbprint (or fingerprint) from certificate
     * @return string
     */
    public function getThumbprint():string
    {
        return $this->fingerprint;
    }
    
    /**
     * Recover used cipher
     * @return string
     */
    public function getCipher():string
    {
        return $this->cipher;
    }
    
    /**
     * Return an array with KEY and IV used to encripy and decript
     * @return array
     */
    public function getKeyAndIV():array
    {
        return ['key' => $this->key, 'iv' => $this->iv];
    }
    
    /**
     * Encrypt message whith generate randon key and iv
     * @param string $datamsg
     * @return string
     */
    public function encryptMsg($datamsg):string
    {
        return openssl_encrypt($datamsg, $this->cipher, $this->key, 0, $this->iv);
    }
    
    /**
     * Decrypt message with private key and randon key and iv
     * @param string $cryptedmsg encrytped message
     * @param string $privateKey in PEM format
     * @param string $keyencrypted key+iv im base64 format
     * @param string $cipher for encrypt and decrypt
     * @return string
     */
    public function decryptMsg($cryptedmsg, $privateKey, $keyencrypted, $cipher = self::AES_128_CBC):string
    {
        openssl_private_decrypt(
            base64_decode($keyencrypted),
            $decryptedkey,
            $privateKey,
            OPENSSL_PKCS1_PADDING
        );
        $key = substr($decryptedkey, 0, strlen($decryptedkey)-16);
        $iv = substr($decryptedkey, -16);
        return openssl_decrypt($cryptedmsg, $this->cipher, $key, 0, $iv);
    }
    
    /**
     * Decrypt message encripted with AES
     * @param string $message
     * @param string $cipher
     * @param string $key
     * @param string $iv
     * @return string
     */
    public function decryptAes($message, $cipher, $key, $iv):string
    {
        return openssl_decrypt($message, $cipher, $key, 0, $iv);
    }
    
    /**
     * Recover certificate info
     * @return array
     */
    public function certificateInfo():array
    {
        $resource = openssl_x509_read($this->certificate);
        $detail = openssl_x509_parse($resource, false);
        $validFrom = \DateTime::createFromFormat('ymdHis\Z', $detail['validFrom']);
        $validTo = \DateTime::createFromFormat('ymdHis\Z', $detail['validTo']);
        return [
          'validFrom' => $validFrom,
          'validTo' => $validTo,
          'details' => $detail
        ];
    }
    
    /**
     * Extract fingerprint from certificate
     * @param string $certificate
     * @return string
     */
    protected function thumbprint($certificate):string
    {
        return openssl_x509_fingerprint($certificate, "sha1", false);
    }
    
    /**
     * Encrypt key with certificate, add iv and converts to base64
     * @param string $key
     * @param string $iv
     * @return string
     */
    protected function encryptkey($key, $iv):string
    {
        openssl_public_encrypt($key.$iv, $cryptedkey, $this->certificate, OPENSSL_PKCS1_PADDING);
        return base64_encode($cryptedkey);
    }
    
    /**
     * Converts certificate in DER format to PEM format
     * @param string $derdata
     * @return string
     */
    protected function convertDERtoPEM($derdata):string
    {
        $certificate = chunk_split(base64_encode($derdata), 64, "\n");
        return "-----BEGIN CERTIFICATE-----\n"
            . $certificate
            ."-----END CERTIFICATE-----\n";
    }
}
