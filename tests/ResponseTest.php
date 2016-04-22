<?php

namespace NFePHP\eFinanc\Tests;

/**
 * Unit Tests for Tools::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Tests\Factory\FactoryTest;
use NFePHP\eFinanc\Response;

class ResponseTest extends FactoryTest
{
    public function testInformacoesCadastrais()
    {
        $xml = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."retornoConsultaCadastro.xml"));
        $method = 'ConsultarInformacoesCadastrais';
        $resp = Response::readReturn($method, $xml);
        $jsonR = json_encode($resp);
        //file_put_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaCadastro.txt", $jsonR);
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaCadastro.txt"));
        $this->assertEquals($expected, $jsonR);
    }
    
    public function testConsultaIntermediario()
    {
        $xml = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."retornoConsultaIntermediario.xml"));
        $method = 'ConsultarInformacoesIntermediario';
        $resp = Response::readReturn($method, $xml);
        $jsonR = json_encode($resp);
        //file_put_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaIntermediario.txt", $jsonR);
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaIntermediario.txt"));
        $this->assertEquals($expected, $jsonR);
    }
    
    public function testConsultaListaEFinanceira()
    {
        $xml = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."retornoConsultaEFinanceira.xml"));
        $method = 'ConsultarListaEFinanceira';
        $resp = Response::readReturn($method, $xml);
        $jsonR = json_encode($resp);
        //file_put_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaEFinanceira.txt", $jsonR);
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaEFinanceira.txt"));
        $this->assertEquals($expected, $jsonR);
    }
    
    public function testConsultaPatrocinado()
    {
        $xml = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."retornoConsultaPatrocinado.xml"));
        $method = 'ConsultarInformacoesPatrocinado';
        $resp = Response::readReturn($method, $xml);
        $jsonR = json_encode($resp);
        //file_put_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaPatrocinado.txt", $jsonR);
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaPatrocinado.txt"));
        $this->assertEquals($expected, $jsonR);
    }
    
    public function testConsultaMovimento()
    {
        $xml = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."retornoConsultaMovimento.xml"));
        $method = 'ConsultarInformacoesMovimento';
        $resp = Response::readReturn($method, $xml);
        $jsonR = json_encode($resp);
        //file_put_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaMovimento.txt", $jsonR);
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."responses".DIRECTORY_SEPARATOR."respConsultaMovimento.txt"));
        $this->assertEquals($expected, $jsonR);
    }
}
