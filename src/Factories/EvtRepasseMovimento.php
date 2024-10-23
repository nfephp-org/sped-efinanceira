<?php

namespace NFePHP\eFinanc\Factories;

use stdClass;
use NFePHP\Common\Certificate;
use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;

class EvtRepasseMovimento extends Factory implements FactoryInterface
{
    /**
     * Constructor
     * @param string $config
     * @param stdClass $std
     * @param Certificate $certificate
     * @param string $data
     */
    public function __construct(
        $config,
        stdClass $std,
        Certificate $certificate = null,
        $data = ''
    ) {
        $params = new \stdClass();
        $params->evtName = 'evtRepasseMovimento';
        $params->evtTag = 'evtRepasseMovimento';
        $params->evtAlias = 'F-1014';
        parent::__construct($config, $std, $params, $certificate, $data);
    }

    protected function toNode()
    {
        return '';
    }
}
