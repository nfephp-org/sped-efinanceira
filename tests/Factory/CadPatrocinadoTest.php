<?php

namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Unit Tests for CadPatrocinado::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Factory\CadPatrocinado;
use NFePHP\eFinanc\Tests\Factory\FactoryTest;

class CadPatrocinadoTest extends FactoryTest
{
    /**
     * @covers NFePHP\eFinanc\Factory\CadPatrocinado::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     */
    public function testInstantiable()
    {
        $evt = new CadPatrocinado($this->config, true);
        $this->assertInstanceOf(CadPatrocinado::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\CadPatrocinado::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\CadPatrocinado::tagInfo
     * @covers NFePHP\eFinanc\Factory\CadPatrocinado::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     * @covers NFePHP\eFinanc\Factory\Factory::assina
     */
    public function testPatrocinado()
    {
        $evt = new CadPatrocinado($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $evt->tagEvento($id, $indRetificacao, $tpAmb);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $giin = "0123456789123456789";
        $cnpj = "11119090910270";
        $nome = 'Fulano de Tal';
        $endereco = 'Rua Cel Silverio Magalhaes';
        $municipio = '3304557';
        $pais = 'BR';
        $paisResidencia = 'BR';
        $evt->tagInfo($giin, $cnpj, $nome, $endereco, $municipio, $pais, $paisResidencia);
        $evt->monta();
        $evt->assina();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtCadPatrocinadoSigned.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtCadPatrocinadoSigned.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::valida
     */
    public function testValida()
    {
        $xml = file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtCadPatrocinadoSigned.xml");
        $evt = new CadPatrocinado($this->config, true);
        $result = $evt->valida($xml);
        $this->assertTrue($result);
    }
    

}
