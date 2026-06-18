<?php

namespace NFePHP\eFinanc\Common\Rest;

use NFePHP\Common\Certificate;

interface RestInterface
{
    //constants
    const SSL_DEFAULT = 0; //default
    const SSL_TLSV1 = 1; //TLSv1
    const SSL_SSLV2 = 2; //SSLv2
    const SSL_SSLV3 = 3; //SSLv3
    const SSL_TLSV1_0 = 4; //TLSv1.0
    const SSL_TLSV1_1 = 5; //TLSv1.1
    const SSL_TLSV1_2 = 6; //TLSv1.2

    /**
     *
     * @param Certificate $certificate
     */
    public function loadCertificate(Certificate $certificate);

    /**
     * Set timeout for connection
     * @param int $timesecs
     */
    public function timeout(int $timesecs);

    /**
     * Set security protocol for soap communications
     * @param int $protocol
     */
    public function protocol(int $protocol = self::SSL_DEFAULT);

    /**
     * Set proxy parameters
     * @param string $ip
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function proxy(string $ip, int $port, string $user, string $password);

    /**
     * Send soap message
     * @param string $url
     * @param string $operation
     * @param string $message
     */
    public function send(string $url, string $operation, string $message = ''): string;
}
