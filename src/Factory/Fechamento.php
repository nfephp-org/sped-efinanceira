<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de fechamento
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\Fechamento
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class Fechamento extends Factory
{

    /**
     * Objeto Dom::class Tag infoCadastro
     * @var Dom
     */
    protected $info;
    /**
     * Objeto Dom::class Tag ReportavelExterior
     * @var Dom
     */
    protected $reportavel;

    /**
     * Array de Objetos Dom::class
     * @var array
     */
    protected $aFech = array();
    
    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtFechamentoeFinanceira';

    /**
     * preconstrutor do xml
     */
    protected function premonta()
    {
        $fechamentoMovOpFin = $this->dom->createElement("FechamentoMovOpFin");
        $this->dom->appChild($fechamentoMovOpFin, $this->reportavel);
        foreach ($this->aFech as $fech) {
            $this->dom->appChild($fechamentoMovOpFin, $fech);
        }
        $this->dom->appChild($this->evt, $fechamentoMovOpFin);
    }
    
    /**
     * Cria a tag Info
     *
     * @param string $dtInicio
     * @param string $dtFim
     * @param string $sitEspecial
     * @return Dom
     */
    public function tagInfo($dtInicio, $dtFim, $sitEspecial)
    {
        $identificador = 'tag infoFechamento ';
        $info = $this->dom->createElement("infoFechamento");
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
        $this->dom->addChild(
            $info,
            "sitEspecial",
            $sitEspecial,
            true,
            $identificador . "sitEspecial "
        );
        $this->info = $info;
        return $info;
    }
    
    /**
     * Cria a tag de reportavelExterior
     * @param string $pais
     * @param string $reportavel
     * @return Dom
     */
    public function reportavelExterior($pais, $reportavel)
    {
        $identificador = 'tag reportavelExterior ';
        $info = $this->dom->createElement("ReportavelExterior");
        $this->dom->addChild(
            $info,
            "pais",
            $pais,
            true,
            $identificador . " "
        );
        $this->dom->addChild(
            $info,
            "reportavel",
            $reportavel,
            true,
            $identificador . " "
        );
        $this->reportavel = $info;
        return $info;
    }
    
    /**
     * Dados de fechamento do anomes
     * @param string $anomes
     * @param string $qtd
     * @return Dom
     */
    public function tagFechamentoMes($anomes, $qtd)
    {
        $identificador = 'tag FechamentoMes ';
        $fech = $this->dom->createElement("FechamentoMes");
        $this->dom->addChild(
            $fech,
            "anoMesCaixa",
            $anomes,
            true,
            $identificador . "Ano e mes"
        );
        $this->dom->addChild(
            $fech,
            "quantArqTrans",
            $qtd,
            true,
            $identificador . "Quantidade arq transf"
        );
        $this->aFech[] = $fech;
        return $fech;
    }
}
