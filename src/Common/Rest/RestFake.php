<?php

namespace NFePHP\eFinanc\Common\Rest;

use NFePHP\Common\Certificate;

final class RestFake extends RestBase
{
    /**
     * Constructor
     * @param Certificate $certificate
     */
    public function __construct(Certificate $certificate = null)
    {
        parent::__construct($certificate);
    }

    /**
     * @param string $url
     * @param string $operation
     * @param string|null $message
     * @return string
     */
    public function send(string $url, string $operation, string $message = null): string
    {
        $method = 'GET';
        if ($operation == 'limparpreprod') {
            $method = 'DELETE';
        }
        if (!empty($message)) {
            $method = 'POST';
        }
        return json_encode([
            'method' => $method,
            'url' => $url,
            'operation' => $operation,
            'message' => $message,
        ], JSON_PRETTY_PRINT);
    }
}
