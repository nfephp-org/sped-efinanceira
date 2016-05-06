<?php

namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Unit Tests for CadDeclarante::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Factory\CadDeclarante;
use NFePHP\eFinanc\Tests\Factory\FactoryTest;

class CadDeclaranteTest extends FactoryTest
{
    /**
     * @covers NFePHP\eFinanc\Factory\CadDeclarante::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     */
    public function testInstantiable()
    {
        $evt = new CadDeclarante($this->config, true);
        $this->assertInstanceOf(CadDeclarante::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\CadDeclarante::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\CadDeclarante::tagInfo
     * @covers NFePHP\eFinanc\Factory\CadDeclarante::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     * @covers NFePHP\eFinanc\Factory\Factory::assina
     */
    public function testDeclarante()
    {
        $evt = new CadDeclarante($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $evt->tagEvento($id, $indRetificacao, $tpAmb);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $giin = '123456.99999.SL.999';
        $nome = 'Fulano de Tal';
        $endereco = 'Rua Cel Silverio Magalhaes';
        $municipio = '3304557';
        $uf = 'SP';
        $pais = 'BR';
        $paisResidencia = 'BR';
        $evt->tagInfo($giin, $nome, $endereco, $municipio, $uf, $pais, $paisResidencia);
        $evt->monta();
        $evt->assina();
        file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtCadDeclaranteSigned.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtCadDeclaranteSigned.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::valida
     */
    public function testValida()
    {
        $xml = file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtCadDeclaranteSigned.xml");
        $evt = new CadDeclarante($this->config, true);
        $result = $evt->valida($xml);
        $this->assertTrue($result);
    }
    
}
