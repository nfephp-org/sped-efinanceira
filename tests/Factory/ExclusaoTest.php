<?php

namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Unit Tests for Exclusao::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Factory\Exclusao;
use NFePHP\eFinanc\Tests\Factory\FactoryTest;

class ExclusaoTest extends FactoryTest
{
    /**
     * @covers NFePHP\eFinanc\Factory\Exclusao::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Uma configuração valida deve ser passada!
     */
    public function testInstantiableFail()
    {
        $evt = new Exclusao();
        $this->assertInstanceOf(Exclusao::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\Exclusao::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     */
    public function testInstantiable()
    {
        $evt = new Exclusao($this->config, true);
        $this->assertInstanceOf(Exclusao::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\Exclusao::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Exclusao::tagInfo
     * @covers NFePHP\eFinanc\Factory\Exclusao::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     * @covers NFePHP\eFinanc\Factory\Factory::assina
     */
    public function testExclusao()
    {
        $evt = new Exclusao($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = ''; //tem que ficar vazio
        $tpAmb = 2;
        $evt->tagEvento($id, $indRetificacao, $tpAmb);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $nrRecibo = '1234-56-789-0123-4501';
        $evt->tagInfo($nrRecibo);
        $evt->monta();
        $evt->assina();
        file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtExclusaoSigned.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtExclusaoSigned.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::valida
     */
    public function testValida()
    {
        $xml = file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtExclusaoSigned.xml");
        $evt = new Exclusao($this->config, true);
        $result = $evt->valida($xml);
        $this->assertTrue($result);
    }
}
