<?php

namespace NFePHP\eFinanc\Tests\Factory;

/**
 * Unit Tests for Abertura::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Factory\Abertura;
use NFePHP\eFinanc\Tests\Factory\FactoryTest;

class AberturaTest extends FactoryTest
{    
    /**
     * @covers NFePHP\eFinanc\Factory\Abertura::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Uma configuração valida deve ser passada!
     */
    public function testInstantiableFail()
    {
        $evt = new Abertura();
        $this->assertInstanceOf(Abertura::class, $evt);
    }
    
    /**
     * @covers NFePHP\eFinanc\Factory\Abertura::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::__construct
     * @covers NFePHP\eFinanc\Factory\Factory::loadConfig
     */
    public function testInstantiable()
    {
        $evt = new Abertura($this->config, true);
        $this->assertInstanceOf(Abertura::class, $evt);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     */
    public function testTagEvento()
    {
        $evt = new Abertura($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $recibo = '';
        $evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);
        $evt->monta();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaIde.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaIde.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     */
    public function testTagDeclarante()
    {
        $evt = new Abertura($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $recibo = '';
        $evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $evt->monta();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaDec.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaDec.xml"));
        $this->assertEquals($expected, $result);
    }

    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Abertura::tagInfo
     * @covers NFePHP\eFinanc\Factory\Abertura::premonta 
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     */
    public function testTagInfo()
    {
        $evt = new Abertura($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $recibo = '';
        $evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $evt->tagInfo('2016-03-01', '2016-04-01');
        $evt->monta();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaInfo.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaInfo.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Abertura::tagInfo
     * @covers NFePHP\eFinanc\Factory\Abertura::tagResponsavelRMF
     * @covers NFePHP\eFinanc\Factory\Abertura::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     */
    public function testTagResponsavelRMF()
    {
        $evt = new Abertura($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $recibo = '';
        $evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $evt->tagInfo('2016-03-01', '2016-04-01');
        $cpf = '00431733813';
        $nome = 'Fulano de Tal';
        $setor = 'SETOR XXXX';
        $ddd = '11';
        $telefone = '99998799';
        $ramal = '';
        $logradouro = 'Rua Cel Silva Pinto';
        $numero = '2000';
        $complemento = '';
        $bairro = 'Jardim das Oliveiras';
        $cep = '04123400';
        $municipio = 'Sao Paulo';
        $uf = 'SP';
        $evt->tagResponsavelRMF($cpf, $nome, $setor, $ddd, $telefone, $ramal, $logradouro, $numero, $complemento, $bairro, $cep, $municipio, $uf);
        $evt->monta();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaRespMRF.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaRespMRF.xml"));
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Abertura::tagInfo
     * @covers NFePHP\eFinanc\Factory\Abertura::tagResponsavelRMF
     * @covers NFePHP\eFinanc\Factory\Abertura::tagRepresLegal
     * @covers NFePHP\eFinanc\Factory\Abertura::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     */
    public function testTagRepresLegal()
    {
        $evt = new Abertura($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $recibo = '';
        $evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $evt->tagInfo('2016-03-01', '2016-04-01');
        $cpf = '00431733813';
        $nome = 'Fulano de Tal';
        $setor = 'SETOR XXXX';
        $ddd = '11';
        $telefone = '99998799';
        $ramal = '';
        $logradouro = 'Rua Cel Silva Pinto';
        $numero = '2000';
        $complemento = '';
        $bairro = 'Jardim das Oliveiras';
        $cep = '04123400';
        $municipio = 'Sao Paulo';
        $uf = 'SP';
        $evt->tagResponsavelRMF($cpf, $nome, $setor, $ddd, $telefone, $ramal, $logradouro, $numero, $complemento, $bairro, $cep, $municipio, $uf);
        $cpf = '00431733813';
        $setor = 'SEILA ';
        $ddd = '11';
        $telefone = '11111111';
        $ramal = '';
        $evt->tagRepresLegal($cpf, $setor, $ddd, $telefone, $ramal);
        $evt->monta();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaRepresLegal.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaRepresLegal.xml"));
        $this->assertEquals($expected, $result);        
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::tagEvento
     * @covers NFePHP\eFinanc\Factory\Factory::tagDeclarante
     * @covers NFePHP\eFinanc\Factory\Abertura::tagInfo
     * @covers NFePHP\eFinanc\Factory\Abertura::tagResponsavelRMF
     * @covers NFePHP\eFinanc\Factory\Abertura::tagRepresLegal
     * @covers NFePHP\eFinanc\Factory\Abertura::premonta
     * @covers NFePHP\eFinanc\Factory\Factory::monta
     * @covers NFePHP\eFinanc\Factory\Factory::assina
     */
    public function testAssina()
    {
        $evt = new Abertura($this->config, true);
        $id = '000000000000000001';
        $indRetificacao = 1;
        $tpAmb = 2;
        $recibo = '';
        $evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);
        $cnpj = '99999090910270';
        $evt->tagDeclarante($cnpj);
        $evt->tagInfo('2016-03-01', '2016-04-01');
        $cpf = '00431733813';
        $nome = 'Fulano de Tal';
        $setor = 'SETOR XXXX';
        $ddd = '11';
        $telefone = '99998799';
        $ramal = '';
        $logradouro = 'Rua Cel Silva Pinto';
        $numero = '2000';
        $complemento = '';
        $bairro = 'Jardim das Oliveiras';
        $cep = '04123400';
        $municipio = 'Sao Paulo';
        $uf = 'SP';
        $evt->tagResponsavelRMF($cpf, $nome, $setor, $ddd, $telefone, $ramal, $logradouro, $numero, $complemento, $bairro, $cep, $municipio, $uf);
        $cpf = '00431733813';
        $setor = 'SEILA ';
        $ddd = '11';
        $telefone = '11111111';
        $ramal = '';
        $evt->tagRepresLegal($cpf, $setor, $ddd, $telefone, $ramal);
        $evt->monta();
        $evt->assina();
        //file_put_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaSignedError.xml", $evt->getXML());
        $result = str_replace("\n", "", $evt->getXML());
        $expected = str_replace("\n", "", file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaSigned.xml"));
        $this->assertEquals($expected, $result);        
    }
    
    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::valida
     */
    public function testValida()
    {
        $xml = file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaSigned.xml");
        $evt = new Abertura($this->config, true);
        $result = $evt->valida($xml);
        $this->assertTrue($result);
    }

    /**
     * @depends testInstantiable
     * @covers NFePHP\eFinanc\Factory\Factory::valida
     */
    public function testValidaFail()
    {
        $xml = file_get_contents($this->pathFixtures."xml".DIRECTORY_SEPARATOR."evtAberturaSignedError.xml");
        $evt = new Abertura($this->config, true);
        $result = $evt->valida($xml);
        $this->assertFalse($result);
    }
}
