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

class Abertura extends Factory
{
    /**
     * Objeto Dom::class Tag evtAberturaeFinanceira
     * @var Dom
     */
    private $evtAberturaeFinanceira;
    /**
     * Objeto Dom::class Tag aberturaMovOpFin
     * @var Dom
     */
    private $aberturaMovOpFin;
    /**
     * Objeto Dom::class Tag ResponsavelRMF
     * @var Dom
     */
    private $respRMF;
    /**
     * Objeto Dom::class Tag RepresLegal
     * @var Dom
     */
    private $replegal;
    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtAberturaeFinanceira';
    
    /**
     * Construtor do XML
     */
    public function monta()
    {
        $this->eFinanceira = $this->dom->createElement("eFinanceira");
        $this->eFinanceira->setAttribute("xmlns", "http://www.eFinanceira.gov.br/schemas/evtAberturaeFinanceira/".$this->objConfig->schemes);
        $this->aberturaMovOpFin = $this->dom->createElement("AberturaMovOpFin");
        $this->dom->appChild($this->aberturaMovOpFin, $this->respRMF, 'Falta DOMDocument');
        $this->dom->appChild($this->aberturaMovOpFin, $this->replegal, 'Falta DOMDocument');
        $this->dom->appChild($this->evtAberturaeFinanceira, $this->aberturaMovOpFin, 'Falta DOMDocument');
        $this->dom->appChild($this->eFinanceira, $this->evtAberturaeFinanceira, 'Falta DOMDocument');
        $this->dom->appChild($this->dom, $this->eFinanceira, 'Falta DOMDocument');
        $this->xml = $this->dom->saveXML();
    }
    
    
    /**
     * Cria a tag evtAberturaeFinanceira/ideEvento
     *
     * @param string $id
     * @param int $indRetificacao
     * @param int $tpAmb
     * @param string $nrRecibo
     * @return Dom
     */
    public function tagAbertura($id, $indRetificacao, $tpAmb, $nrRecibo = '')
    {
        $id = "ID".str_pad($id, 18, '0', STR_PAD_LEFT);
        $identificador = 'tag raiz ';
        $this->evtAberturaeFinanceira = $this->dom->createElement("evtAberturaeFinanceira");
        //importante a identificação "Id" deve estar grafada assim
        $this->evtAberturaeFinanceira->setAttribute("Id", $id);
        $ide = $this->dom->createElement("ideEvento");
        $this->dom->addChild(
            $ide,
            "indRetificacao",
            $indRetificacao,
            true,
            $identificador . "Indicador de retificação"
        );
        if ($indRetificacao > 1) {
            $this->dom->addChild(
                $ide,
                "nrRecibo",
                $nrRecibo,
                true,
                $identificador . "Numero do recibo quando for retificador"
            );
        }
        $this->dom->addChild(
            $ide,
            "tpAmb",
            $tpAmb,
            true,
            $identificador . "Indicador de retificação"
        );
        $this->dom->addChild(
            $ide,
            "aplicEmi",
            $this->objConfig->aplicEmi,
            true,
            $identificador . "Aplicativo de emissão do evento"
        );
        $this->dom->addChild(
            $ide,
            "verAplic",
            $this->objConfig->verAplic,
            true,
            $identificador . "Versão do aplicativo de emissão do evento"
        );
        $this->dom->appChild($this->evtAberturaeFinanceira, $ide);
        return $this->evtAberturaeFinanceira;
    }
    
    /**
     * Cria a tag ideDeclarante
     *
     * @param string $cnpj
     * @return Dom
     */
    public function tagDeclarante($cnpj)
    {
        $identificador = 'tag ideDeclarante ';
        $ide = $this->dom->createElement("ideDeclarante");
        $this->dom->addChild(
            $ide,
            "cnpjDeclarante",
            $cnpj,
            true,
            $identificador . "Informar CNPJ da Empresa Declarante"
        );
        $this->dom->appChild($this->evtAberturaeFinanceira, $ide);
        return $ide;
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
        $this->dom->appChild($this->evtAberturaeFinanceira, $info);
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
