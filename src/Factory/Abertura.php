<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de abertura financeira
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\Abertura
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class Abertura extends Factory
{

    /**
     * Objeto Dom::class Tag ResponsavelRMF
     * @var Dom
     */
    protected $respRMF;
    /**
     * Objeto Dom::class Tag RepresLegal
     * @var Dom
     */
    protected $replegal;
    /**
     * Objeto Dom::class Tag info
     * @var Dom
     */
    protected $info;
    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtAberturaeFinanceira';
    
    /**
     * preConstrutor do XML
     */
    protected function premonta()
    {
        $aberturaMovOpFin = $this->dom->createElement("AberturaMovOpFin");
        $this->dom->appChild($aberturaMovOpFin, $this->respRMF, 'Falta DOMDocument');
        $this->dom->appChild($aberturaMovOpFin, $this->replegal, 'Falta DOMDocument');
        $this->dom->appChild($this->evt, $aberturaMovOpFin, 'Falta DOMDocument');
    }
    
    /**
     * Cria a tag Info
     *
     * @param string $dtInicio
     * @param strinf $dtFim           ultimo dia do semestre de ref
     * @return Dom
     */
    public function tagInfo($dtInicio, $dtFim)
    {
        $identificador = 'tag infoAbertura ';
        $info = $this->dom->createElement("infoAbertura");
        $this->dom->addChild(
            $info,
            "dtInicio",
            $dtInicio,
            true,
            $identificador . " "
        );
        $this->dom->addChild(
            $info,
            "dtFim",
            $dtFim,
            true,
            $identificador . " "
        );
        $this->info = $info;
        return $info;
    }
    
    /**
     * Cria a tag ResponsavelRMF
     *
     * @param string $cpf
     * @param string $nome
     * @param string $setor
     * @param string $ddd
     * @param string $telefone
     * @param string $ramal
     * @param string $logradouro
     * @param string $numero
     * @param string $complemento
     * @param string $bairro
     * @param string $cep
     * @param string $municipio
     * @param string $uf
     * @return Dom
     */
    public function tagResponsavelRMF($cpf, $nome, $setor, $ddd, $telefone, $ramal, $logradouro, $numero, $complemento, $bairro, $cep, $municipio, $uf)
    {
        $identificador = 'tag ResponsavelRMF ';
        $this->respRMF = $this->dom->createElement("ResponsavelRMF");
        $this->dom->addChild(
            $this->respRMF,
            "CPF",
            $cpf,
            true,
            $identificador . "Numero de CPF"
        );
        $this->dom->addChild(
            $this->respRMF,
            "Nome",
            $nome,
            true,
            $identificador . "Nome"
        );
        $this->dom->addChild(
            $this->respRMF,
            "Setor",
            $setor,
            true,
            $identificador . "Setor"
        );
        //monta a tag referente ao telefone
        $tagtel = $this->dom->createElement("Telefone");
        $this->dom->addChild(
            $tagtel,
            "DDD",
            $ddd,
            true,
            $identificador . "DDD do telefone"
        );
        $this->dom->addChild(
            $tagtel,
            "Numero",
            $telefone,
            true,
            $identificador . "Numero do telefone "
        );
        $this->dom->addChild(
            $tagtel,
            "Ramal",
            $ramal,
            false,
            $identificador . "Ramal do telefone"
        );
        $this->dom->appChild($this->respRMF, $tagtel, 'ResponsavelRMF');
        //monta a tag referentes ao endereço
        $end = $this->dom->createElement("Endereco");
        $this->dom->addChild(
            $end,
            "Logradouro",
            $logradouro,
            true,
            $identificador . "Logradouro do endereço"
        );
        $this->dom->addChild(
            $end,
            "Numero",
            $numero,
            true,
            $identificador . "Numero do endereço"
        );
        $this->dom->addChild(
            $end,
            "Complemento",
            $complemento,
            false,
            $identificador . "Complemento do endereço"
        );
        $this->dom->addChild(
            $end,
            "Bairro",
            $bairro,
            true,
            $identificador . "Bairro do endereço"
        );
        $this->dom->addChild(
            $end,
            "CEP",
            $cep,
            true,
            $identificador . "CEP do endereço"
        );
        $this->dom->addChild(
            $end,
            "Municipio",
            $municipio,
            true,
            $identificador . "Municipio do endereço"
        );
        $this->dom->addChild(
            $end,
            "UF",
            $uf,
            true,
            $identificador . "UF do endereço"
        );
        $this->dom->appChild($this->respRMF, $end, 'ResponsavelRMF');
        return $this->respRMF;
    }
    
    /**
     * Cria a tag RepresLegal
     *
     * @param string $cpf
     * @param string $setor
     * @param string $ddd
     * @param string $telefone
     * @param string $ramal
     * @return Dom
     */
    public function tagRepresLegal($cpf, $setor, $ddd, $telefone, $ramal)
    {
        $identificador = 'tag RepresLegal ';
        $this->replegal = $this->dom->createElement("RepresLegal");
        $this->dom->addChild(
            $this->replegal,
            "CPF",
            $cpf,
            true,
            $identificador . " "
        );
        $this->dom->addChild(
            $this->replegal,
            "Setor",
            $setor,
            true,
            $identificador . " "
        );
        
        $tagtel = $this->dom->createElement("Telefone");
        $this->dom->addChild(
            $tagtel,
            "DDD",
            $ddd,
            true,
            $identificador . "DDD do telefone"
        );
        $this->dom->addChild(
            $tagtel,
            "Numero",
            $telefone,
            true,
            $identificador . "Numero do telefone"
        );
        $this->dom->addChild(
            $tagtel,
            "Ramal",
            $ramal,
            false,
            $identificador . "Ramal do telefone"
        );
        $this->dom->appChild($this->replegal, $tagtel, 'RepresLegal');
        return $this->replegal;
    }
}
