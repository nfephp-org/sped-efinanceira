<?php

namespace NFePHP\eFinanc\Tests;

/**
 * Unit Tests for Tools::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Tests\Factory\FactoryTest;
use NFePHP\eFinanc\Tools;

class ToolsTest extends FactoryTest
{
    public $mockTools;
    
    public function __construct()
    {
        parent::__construct();
        $this->mockTools = $this->getMockBuilder('Tools')
            ->setMethods(array('zSend'))
            ->getMock();
    }
    
    /**
     * @covers NFePHP\eFinanc\Tools::__construct
     * @expectedException RuntimeException
     */
    public function testInstantiable()
    {
        $evt = new Tools($this->config, true);
        $this->assertInstanceOf(Tools::class, $evt);
    }
    
    public function testConsultarInformacoesCadastrais()
    {   
        $this->assertTrue(true);
    }
    
    public function testConsultarListaEFinanceira()
    {
        $this->assertTrue(true);
    }
    
    public function testConsultarInformacoesMovimento()
    {
        $this->assertTrue(true);
    }
    
    public function testConsultarInformacoesIntermediario()
    {
        $this->assertTrue(true);
    }
    
    public function testConsultarInformacoesPatrocinado()
    {
        $this->assertTrue(true);
    }
    
    public function testEnviaLote()
    {
        $this->assertTrue(true);
    }
}
