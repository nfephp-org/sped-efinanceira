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
            $parameters = [
                'Accept: application/xml'
            ];
            $this->saveTemporarilyKeyFiles();
            $handle = curl_init();
            if ($handle !== false) {
                throw new SoapException('Erro ao inicializar o cURL', 500);
            }
            $this->setCurlProxy($handle);
            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->timeout);
            curl_setopt($handle, CURLOPT_TIMEOUT, $this->timeout + 20);
            curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($handle, CURLOPT_HEADER, 1);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($handle, CURLOPT_SSLVERSION, $this->sslprotocol);
            curl_setopt($handle, CURLOPT_SSLCERT, $this->tempdir . $this->certfile);
            curl_setopt($handle, CURLOPT_SSLKEY, $this->tempdir . $this->prifile);
            if (!empty($this->temppass)) {
                curl_setopt($handle, CURLOPT_KEYPASSWD, $this->temppass);
            }
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            if (!empty($message)) {
                $parameters = array_merge(['Content-Type: application/xml'], $parameters);
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $message);
                //curl_setopt($handle, CURLOPT_HTTPHEADER, $parameters);
            } else {
                //curl_setopt($handle, CURLOPT_POST, false);
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
                if ($operation == 'limparpreprod') {
                    curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "DELETE");
                }
            }
            curl_setopt($handle, CURLOPT_HTTPHEADER, $parameters);
            $response = curl_exec($handle);
            $this->resterror = curl_error($handle);
            $this->resterrorno = (int) curl_errno($handle);
            $ainfo = curl_getinfo($handle);
            if (is_array($ainfo)) {
                $this->restinfo = $ainfo;
            }
            $headsize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
            $httpcode = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);
            $this->responseHead = trim(substr($response, 0, $headsize));
            $this->responseBody = trim(substr($response, $headsize));
        } catch (\Exception $e) {
            throw new SoapException('LIBCURL não localizada', 500);
        }
        if ($this->resterror != '') {
            throw new SoapException($this->resterror . " [$url]", $this->resterrorno);
        }
        if ($httpcode !== 200 && $httpcode !== 201) {
            $err = [
                405 => 'Método HTTP incorreto. Use o método POST para enviar lotes.',
                413 => 'Tamanho da mensagem é maior que o permitido.',
                415 => 'Media type não é application/xml, ou o conteúdo do body informado não é um XML.',
                422 => 'Lote não foi recebido pois possui inconsistências. No body é retornado o XML com as ocorrências'
                    . ' a serem resolvidas pela instituição declarante.',
                429 => 'Excesso de conexões em sequência. Aguarde alguns minutos e tente novamente.',
                495 => 'Certificado não aceito na conexão à API. Verifique se o certificado está expirado ou revogado.',
                496 => 'Certificado não foi informado na conexão à API.',
                500 => 'Erro interno na e-Financeira. XML retornado com código de erro para acionamento.',
                503 => 'Serviço indisponível momentaneamente. Aguarde alguns minutos e tente novamente.'
            ];
            $msg = $err[$httpcode] ?? 'Erro desconhecido';
            throw new SoapException(
                " [$url] HTTPError {$msg} HTTPCode: $httpcode -  {$this->responseBody}",
                $httpcode
            );
        }
        return !empty($this->responseBody)
            ? $this->responseBody
            : "HTTPCode: $httpcode - Operação realizada com sucesso.";
    }

    /**
     * Set proxy into cURL parameters
     * @param \CurlHandle $handle
     * @return void
     */
    private function setCurlProxy(&$handle)
    {
        if ($this->proxyIP != '') {
            curl_setopt($handle, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($handle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($handle, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPort);
            if ($this->proxyUser != '') {
                curl_setopt($handle, CURLOPT_PROXYUSERPWD, $this->proxyUser.':'.$this->proxyPass);
                curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
    }
}
