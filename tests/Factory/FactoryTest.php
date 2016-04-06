<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Description of FactoryTest
 *
 * @author administrador
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public $config = '';
    public $pathFixtures;
    
    public function __construct()
    {
        $this->pathFixtures = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."fixtures".DIRECTORY_SEPARATOR;
        $path = $this->pathFixtures."certs".DIRECTORY_SEPARATOR;
        $this->config = "{
            \"atualizacao\":\"2016-04-01 09:00:17\",
            \"tpAmb\":2,
            \"pathFiles\":\"\/tmp\/eFinanc\/\",
            \"pathCertsFiles\":\"$path\",
            \"siteUrl\":\"http:\/\/localhost\/sped-efinanceira\/\",
            \"schemes\":\"v1_0_1\",
            \"verAplic\":\"001\",
            \"aplicEmi\":\"1\",
            \"razaosocial\":\"Sua empresa Ltda\",
            \"siglaUF\":\"SP\",
            \"cnpj\":\"99999090910270\",
            \"certPfxName\":\"certificado.pfx\",
            \"certPassword\":\"associacao\",
            \"certPhrase\":\"\",
            \"aProxyConf\":{
                \"proxyIp\":\"\",
                \"proxyPort\":\"\",
                \"proxyUser\":\"\",
                \"proxyPass\":\"\"
            }
        }";
    }
    
    public function testBase()
    {
        $this->assertTrue(true);
    }
}
