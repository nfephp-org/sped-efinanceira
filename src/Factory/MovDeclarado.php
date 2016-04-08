<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Movimento Declarado
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\MovDeclarado
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class MovDeclarado extends Factory
{
    /**
     * Conjunto de NIF do declarado
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aDeclaradoNIF = array();
    /**
     * Conjunto de paises de residencia do declarado
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aDeclaradoPaisResid = array();
    /**
     * Conjunto de Nacionalidades do declarado
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aDeclaradoPaisNac = array();
    /**
     * Array de tipo de declarado como strings
     *
     * @var array
     */
    protected $aTpdeclarado = array();
    /**
     * Simulacro pois não existe info no movimento
     *
     * @var string
     */
    protected $info = '';
    /**
     * Objeto Dom::class Tag ideDeclarado
     *
     * @var Dom
     */
    protected $ideDeclarado;
    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtMovOpFin';

    /**
     * Premonta a tag do Declarado
     */
    protected function premonta()
    {
        if (!empty($this->ideDeclarado)) {
            foreach ($this->aTpdeclarado as $tpD) {
                $temp = $this->dom->createElement('tpDeclarado', $tpD);
                $this->dom->appChildBefore($this->ideDeclarado, $temp, 'NIDeclarado');
            }
            foreach ($this->aDeclaradoNIF as $nif) {
                $this->dom->appChildBefore($this->ideDeclarado, $nif, 'NomeDeclarado');
            }
            foreach ($this->aDeclaradoPaisResid as $pais) {
                $this->dom->appChild($this->ideDeclarado, $pais);
            }
            foreach ($this->aDeclaradoPaisNac as $pais) {
                $this->dom->appChild($this->ideDeclarado, $pais);
            }
            $this->dom->appChild($this->evt, $this->ideDeclarado);
        }
    }
    
    /**
     * Cria as tags NIF do declarado
     * podem existir ZERO ou mais
     *
     * @param string $numeroNIF
     * @param string $paisEmissao
     * @return Dom tag NIF
     */
    public function declaradoNIF($numeroNIF, $paisEmissao)
    {
        $nif = $this->zNIF($numeroNIF, $paisEmissao);
        $this->aDeclaradoNIF[] = $nif;
        return $nif;
    }
    
    /**
     * Crias as tags PaisResid do declarado
     * podem existir ZERO ou mais
     *
     * @param string $pais
     * @return Dom tag PaisResid
     */
    public function declaradoPaisResid($pais)
    {
        $tpais = $this->zPais($pais, 'PaisResid');
        $this->aDeclaradoPaisResid[] = $tpais;
        return $tpais;
    }
    
    /**
     * Crias as tags PaisNacionalidade do declarado
     * podem existir ZERO ou mais
     *
     * @param string $pais
     * @return Dom tag PaisResid
     */
    public function declaradoPaisNac($pais)
    {
        $tpais = $this->zPais($pais, 'PaisNacionalidade');
        $this->aDeclaradoPaisNac[] = $tpais;
        return $tpais;
    }
    
    /**
     * Cria array com os tipos de declarado
     * podem ser ZERO ou mais
     *
     * @param string $tpDeclarado
     * @return string
     */
    public function declaradoTipo($tpDeclarado)
    {
        $this->aTpdeclarado[] = $tpDeclarado;
        return $tpDeclarado;
    }
    
    /**
     * Cria a tag ideDeclarado
     *
     * @param string $tpNI          Obrigatorio
     * @param string $nIDeclarado   Obrigatorio
     * @param string $nomeDeclarado Obrigatorio
     * @param string $dataNasc      Obrigatorio
     * @param string $enderecoLivre se não exisitr deixar uma string vazia
     * @param string $pais          Obrigatorio
     * @return Dom
     */
    public function declarado(
        $tpNI,
        $nIDeclarado,
        $nomeDeclarado,
        $dataNasc,
        $enderecoLivre,
        $pais
    ) {
        $identificador = 'tag ideDeclarado ';
        $this->ideDeclarado = $this->dom->createElement("ideDeclarado");
        $this->dom->addChild(
            $this->ideDeclarado,
            "tpNI",
            $tpNI,
            true,
            $identificador . "tipo de NI "
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "NIDeclarado",
            $nIDeclarado,
            true,
            $identificador . "NI do declarado "
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "NomeDeclarado",
            $nomeDeclarado,
            true,
            $identificador . "Nome do Declarado"
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "DataNasc",
            $dataNasc,
            false,
            $identificador . "Nome do Declarado"
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "EnderecoLivre",
            $enderecoLivre,
            false,
            $identificador . "Endereco Livre do Declarado"
        );
        $gpais = $this->dom->createElement("PaisEndereco");
        $this->dom->addChild(
            $gpais,
            "Pais",
            $pais,
            true,
            $identificador . "Pais Endereco do Declarado"
        );
        $this->dom->appChild($this->ideDeclarado, $gpais);
        return $this->ideDeclarado;
    }
    
    /**
     * Cria as tags NIF do declarado e proprietario
     * podem existir ZERO ou mais
     *
     * @param string $numeroNIF
     * @param string $paisEmissao
     * @return Dom tag NIF
     */
    protected function zNIF($numeroNIF, $paisEmissao)
    {
        $nif = $this->dom->createElement("NIF");
        $this->dom->addChild(
            $nif,
            "NumeroNIF",
            $numeroNIF,
            true,
            "numero NIF "
        );
        $this->dom->addChild(
            $nif,
            "PaisEmissaoNIF",
            $paisEmissao,
            true,
            "Pais de Emissao do NIF "
        );
        return $nif;
    }
    
    /**
     * Cria o conjunto de tags  MedJudic
     * Podem existir ZERO ou mais desse tipo
     *
     * @param string $numProcJud  Obrigatorio
     * @param string $vara        Obrigatorio
     * @param string $secJud      Obrigatorio
     * @param string $subSecJud   Obrigatorio
     * @param string $dtConcessao Obrigatorio
     * @param string $dtCassacao  caso não exista deixe uma string vazia
     * @return Dom tag MedJudic
     */
    protected function zMedJudic(
        $numProcJud,
        $vara,
        $secJud,
        $subSecJud,
        $dtConcessao,
        $dtCassacao
    ) {
        $medJudic = $this->dom->createElement("MedJudic");
        $this->dom->addChild(
            $medJudic,
            "NumProcJud",
            $numProcJud,
            true,
            "Número do Processo Judicial "
        );
        $this->dom->addChild(
            $medJudic,
            "Vara",
            $vara,
            true,
            "Vara de Tramitação "
        );
        $this->dom->addChild(
            $medJudic,
            "SecJud",
            $secJud,
            true,
            "Seção judiciária "
        );
        $this->dom->addChild(
            $medJudic,
            "SubSecJud",
            $subSecJud,
            true,
            "SubSeção judiciária "
        );
        $this->dom->addChild(
            $medJudic,
            "dtConcessao",
            $dtConcessao,
            true,
            "Data da Concessão "
        );
        $this->dom->addChild(
            $medJudic,
            "dtCassacao",
            $dtCassacao,
            false,
            "Data da Data da Cassação "
        );
        return $medJudic;
    }
    
    /**
     * Cria as tags Pais
     * podem existir ZERO ou mais
     *
     * @param string $pais
     * @param string $tag  PaisNacionalidade ou PaisResid
     * @return Dom tag PaisNacionalidade ou PaisResid
     */
    protected function zPais($pais, $tag)
    {
        $paisNac = $this->dom->createElement($tag);
        $this->dom->addChild(
            $paisNac,
            "Pais",
            $pais,
            true,
            "Pais $tag do Declarado"
        );
        return $paisNac;
    }
}
