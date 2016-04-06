<?php
namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Unit Tests for Abertura::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Factory\Abertura;

class AberturaTest extends \PHPUnit_Framework_TestCase
{
    public $config = '{"atualizacao":"2016-04-01 09:00:17","tpAmb":2,
        "pathFiles":"\/tmp\/eFinanc\/",
        "pathCertsFiles":"..\/fixtures\/certs\/",
        "siteUrl":"http:\/\/localhost\/sped-efinanceira\/",
        "schemes":"v1_0_1",
        "verAplic":"001",
        "aplicEmi":"1",
        "razaosocial":"Sua empresa Ltda",
        "siglaUF":"SP",
        "cnpj":"99999090910270",
        "certPfxName":"certificado.pfx",
        "certPassword":"associacao",
        "certPhrase":"",
        "aProxyConf":{
            "proxyIp":"",
            "proxyPort":"",
            "proxyUser":"",
            "proxyPass":""
        }
    }';
    
    /**
     * @covers NFePHP\eFinanc\Factory\Abertura::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Uma configuração valida deve ser passada!
     */
    public function testInstantiableFail()
    {
        $evtr = new Abertura();
        $this->assertInstanceOf(Abertura::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\Abertura::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     */
    public function testInstantiable()
    {
        $evtr = new Abertura($this->config);
        $this->assertInstanceOf(Abertura::class, $evt);
    }
}
