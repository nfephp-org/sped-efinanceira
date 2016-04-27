<?php

namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Unit Tests for Fechamento::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Factory\Fechamento;
use NFePHP\eFinanc\Tests\Factory\FactoryTest;

class FechamentoTest extends FactoryTest
{
    /**
     * @covers NFePHP\eFinanc\Factory\Fechamento::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     */
    public function testInstantiable()
    {
        $evt = new Fechamento($this->config, true);
        $this->assertInstanceOf(Fechamento::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\Fechamento::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Fechamento::tagInfo
     * @covers NFePHP\eFinanc\Factory\Fechamento::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     * @covers NFePHP\eFinanc\Factory\Factory::assina
     */
    public function testFechamento()
    {
        $evt = new Fechamento($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $evt->tagEvento($id, $indRetificacao, $tpAmb);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $dtInicio = '2016-01-01';
        $dtFim = '2016-06-30';
        $sitEspecial = '0';
        $evt->tagInfo($dtInicio, $dtFim, $sitEspecial);
        $pais = 'US';
        $reportavel = '0';
        $evt->reportavelExterior($pais, $reportavel);
        $aFech = [
            ['201601', '1'],
            ['201602', '1'],
            ['201603', '1'],
            ['201604', '1'],
            ['201605', '1'],
            ['201606', '1']
        ];
        foreach ($aFech as $fech) {
            $evt->tagFechamentoMes($fech[0], $fech[1]);
        }        
        $evt->monta();
        $evt->assina();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtFechamentoSigned.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtFechamentoSigned.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::valida
     */
    public function testValida()
    {
        $xml = file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtFechamentoSigned.xml");
        $evt = new Fechamento($this->config, true);
        $result = $evt->valida($xml);
        $this->assertTrue($result);
    }

}
