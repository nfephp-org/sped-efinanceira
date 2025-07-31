<?php

namespace NFePHP\eFinanc\Common\Rest;

use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\SoapException;

final class RestCurl extends RestBase
{
    public function __construct(Certificate $certificate = null)
    {
        parent::__construct($certificate);
    }

    /**
     * Envia a solicitação atraves do cURL
     * @param string $url
     * @param string|null $message
     * @return string
     */
    public function send(string $url, string $operation, string $message = null): string
    {
        $response = '';
        try {
            $parameters = [];
            $this->saveTemporarilyKeyFiles();
            $oCurl = curl_init();
            $this->setCurlProxy($oCurl);
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, $this->timeout + 20);
            curl_setopt($oCurl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            if (!$this->disablesec) {
                curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);
                if (is_file($this->casefaz ?? '')) {
                    curl_setopt($oCurl, CURLOPT_CAINFO, $this->casefaz);
                }
            }
            curl_setopt($oCurl, CURLOPT_SSLVERSION, $this->sslprotocol);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $this->tempdir . $this->certfile);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $this->tempdir . $this->prifile);
            if (!empty($this->temppass)) {
                curl_setopt($oCurl, CURLOPT_KEYPASSWD, $this->temppass);
            }
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
            if (! empty($message)) {
                curl_setopt($oCurl, CURLOPT_POST, true);
                curl_setopt($oCurl, CURLOPT_POSTFIELDS, $message);
                curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parameters);
            } else {
                //curl_setopt($oCurl, CURLOPT_POST, false);
                curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, "GET");
            }
            $response = curl_exec($oCurl);
            $this->resterror = curl_error($oCurl);
            $this->resterrorno = (int) curl_errno($oCurl);
            $ainfo = curl_getinfo($oCurl);
            if (is_array($ainfo)) {
                $this->restinfo = $ainfo;
            }
            $headsize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
            $httpcode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
            curl_close($oCurl);
            $this->responseHead = trim(substr($response, 0, $headsize));
            $this->responseBody = trim(substr($response, $headsize));
        } catch (\Exception $e) {
            throw new SoapException('LIBCURL não localizada', 500);
        }
        if ($this->resterror != '') {
            throw new SoapException($this->resterror . " [$url]", $this->resterrorno);
        }
        if ($httpcode != 200) {
            throw new SoapException(" [$url] HTTP Error code: $httpcode -  {$this->responseBody}", $httpcode);
        }
        return $this->responseBody;
    }

    /**
     * Set proxy into cURL parameters
     * @param resource $oCurl
     * @return void
     */
    private function setCurlProxy(&$oCurl)
    {
        if ($this->proxyIP != '') {
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPort);
            if ($this->proxyUser != '') {
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUser.':'.$this->proxyPass);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
    }
}
