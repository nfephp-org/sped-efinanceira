<?php

namespace NFePHP\eFinanc\Common\Rest;

use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\RuntimeException;
use NFePHP\Common\Files;
use NFePHP\Common\Strings;

abstract class RestBase implements RestInterface
{
    /**
     * @var int
     */
    protected $sslprotocol = self::SSL_DEFAULT;
    /**
     * @var int
     */
    protected $timeout = 60;
    /**
     * @var string
     */
    protected $proxyIP;
    /**
     * @var int
     */
    protected $proxyPort;
    /**
     * @var string
     */
    protected $proxyUser;
    /**
     * @var string
     */
    protected $proxyPass;
    /**
     * @var Certificate|null
     */
    protected $certificate;
    /**
     * @var string
     */
    protected $tempdir;
    /**
     * @var string
     */
    protected $certsdir;
    /**
     * @var string
     */
    protected $prifile;
    /**
     * @var string
     */
    protected $pubfile;
    /**
     * @var string
     */
    protected $certfile;
    /**
     * @var string
     */
    protected $casefaz;
    /**
     * @var bool
     */
    protected $disablesec = false;
    /**
     * @var bool
     */
    protected $disableCertValidation = false;
    /**
     * @var Files
     */
    protected $filesystem;
    /**
     * @var string
     */
    protected $temppass = '';
    /**
     * @var bool
     */
    protected $encriptPrivateKey = true;
    /**
     * @var string
     */
    public $responseHead;
    /**
     * @var string
     */
    public $responseBody;
    /**
     * @var string
     */
    public $requestHead;
    /**
     * @var string
     */
    public $requestBody;
    /**
     * @var string
     */
    public $resterror;
    /**
     * @var int
     */
    public $resterrorno;
    /**
     * @var array
     */
    public $restinfo = [];
    /**
     * @var int
     */
    public $waitingTime = 45;

    /**
     * Constructor
     * @param Certificate|null $certificate
     */
    public function __construct(Certificate $certificate = null)
    {
        $this->certificate = $certificate;
        $this->setTemporaryFolder(sys_get_temp_dir() . '/sped/');
    }

    /**
     * Check if certificate is valid
     * @param Certificate $certificate
     * @return mixed
     * @throws RuntimeException
     */
    private function checkCertValidity(Certificate $certificate = null)
    {
        if ($this->disableCertValidation) {
            return $certificate;
        }
        if (!empty($certificate)) {
            if ($certificate->isExpired()) {
                throw new RuntimeException(
                    'The validity of the certificate has expired.'
                );
            }
        }
        return $certificate;
    }

    /**
     * Destructor
     * Clean temporary files
     */
    public function __destruct()
    {
        $this->removeTemporarilyFiles();
    }

    /**
     * Disables the security checking of host and peer certificates
     * @param bool $flag
     */
    public function disableSecurity($flag = false)
    {
        $this->disablesec = $flag;
        return $this->disablesec;
    }

    /**
     * ONlY for tests
     * @param bool $flag
     * @return bool
     */
    public function disableCertValidation($flag = true)
    {
        $this->disableCertValidation = $flag;
        return $this->disableCertValidation;
    }

    /**
     * Load path to CA and enable to use on SOAP
     * @param string $capath
     */
    public function loadCA($capath)
    {
        if (is_file($capath)) {
            $this->casefaz = $capath;
        }
    }

    /**
     * Set option to encript private key before save in filesystem
     * for an additional layer of protection
     * @param bool $encript
     * @return bool
     */
    public function setEncriptPrivateKey($encript = true)
    {
        return $this->encriptPrivateKey = $encript;
    }

    /**
     * Set another temporayfolder for saving certificates for SOAP utilization
     * @param string $folderRealPath
     */
    public function setTemporaryFolder($folderRealPath)
    {
        $this->tempdir = $folderRealPath;
        $this->setLocalFolder($folderRealPath);
    }

    /**
     * Set Local folder for flysystem
     * @param string $folder
     * @throws \Exception
     */
    protected function setLocalFolder($folder = '')
    {
        $this->filesystem = new Files($folder);
    }

    /**
     * Set certificate class for SSL comunications
     * @param Certificate $certificate
     */
    public function loadCertificate(Certificate $certificate)
    {
        $this->certificate = $this->checkCertValidity($certificate);
    }

    /**
     * Set timeout for communication
     * @param int $secs
     * @return int
     */
    public function timeout(int $secs = 20): int
    {
        return $this->timeout = $secs;
    }

    /**
     * Set security protocol
     * @param int $protocol
     * @return int
     */
    public function protocol(int $protocol = self::SSL_DEFAULT): int
    {
        return $this->sslprotocol = $protocol;
    }

    /**
     * Set proxy parameters
     * @param string $ip
     * @param int $port
     * @param string $user
     * @param string $password
     * @return void
     */
    public function proxy(string $ip, int $port, string $user, string $password)
    {
        $this->proxyIP = $ip;
        $this->proxyPort = $port;
        $this->proxyUser = $user;
        $this->proxyPass = $password;
    }

    /**
     * Send message to webservice
     * @param string $url
     * @param string $operation
     * @param string|null $message
     * @return string
     */
    abstract public function send(string $url, string $operation, string $message = null): string;

    /**
     * Temporarily saves the certificate keys for use cURL or SoapClient
     * @return void
     * @throws \Exception
     */
    public function saveTemporarilyKeyFiles()
    {
        if (empty($this->certificate)) {
            throw new RuntimeException(
                'Certificate not found.'
            );
        }
        $this->certsdir = $this->certificate->getCnpj() . '/certs/';
        $this->prifile = $this->certsdir. Strings::randomString(10).'.pem';
        $this->pubfile = $this->certsdir . Strings::randomString(10).'.pem';
        $this->certfile = $this->certsdir . Strings::randomString(10).'.pem';
        $ret = true;
        $private = $this->certificate->privateKey;
        if ($this->encriptPrivateKey) {
            //cria uma senha temporária ALEATÓRIA para salvar a chave primaria
            //portanto mesmo que localizada e identificada não estará acessível
            //pois sua senha não existe além do tempo de execução desta classe
            $this->temppass = Strings::randomString(16);
            //encripta a chave privada entes da gravação do filesystem
            openssl_pkey_export(
                $this->certificate->privateKey,
                $private,
                $this->temppass
            );
        }
        $ret &= $this->filesystem->put(
            $this->prifile,
            $private
        );
        $ret &= $this->filesystem->put(
            $this->pubfile,
            $this->certificate->publicKey
        );
        $ret &= $this->filesystem->put(
            $this->certfile,
            $private."{$this->certificate}"
        );
        if (!$ret) {
            throw new RuntimeException(
                'Unable to save temporary key files in folder.'
            );
        }
    }

    /**
     * Delete all files in folder
     * @return void
     * @throws \Exception
     */
    public function removeTemporarilyFiles()
    {
        $files = [
            $this->prifile,
            $this->pubfile,
            $this->certfile
        ];
        foreach ($files as $file) {
            if (empty($file)) {
                continue;
            }
            if ($this->filesystem->has($file)) {
                $this->filesystem->delete($file);
            }
        }
    }
}
