<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Cadastro dos Intermediarios
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc\Factory\CadIntermediario
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class CadIntermediario extends Factory
{

    /**
     * Objeto Dom::class Tag infoCadastro
     *
     * @var Dom
     */
    protected $info;
    
    
    /**
     * estabelece qual a tag será assinada
     *
     * @var string
     */
    protected $signTag = 'evtCadIntermediario';
   
    /**
     * Faz a premontagem se necessário
     *
     * @return
     */
    protected function premonta()
    {
        return;
    }
    
    /**
     * Cria a tag Info
     *
     * @param  string $giin
     * @param  string $tpNI
     * @param  string $nIIntermediario
     * @param  string $nome
     * @param  string $endereco
     * @param  string $pais
     * @param  string $paisResidencia
     * @return Dom
     */
    public function tagInfo($giin, $tpNI, $nIIntermediario, $nome, $endereco, $pais, $paisResidencia)
    {
        $identificador = 'tag infoIntermediario ';
        $info = $this->dom->createElement("infoIntermediario");
        $this->dom->addChild(
            $info,
            "GIIN",
            $giin,
            true,
            $identificador . "Informar GIIN (Global Intermediary Identification Number)"
        );
        $this->dom->addChild(
            $info,
            "tpNI",
            $tpNI,
            true,
            $identificador . "Tipo de NI do intermediário"
        );
        $this->dom->addChild(
            $info,
            "NIIntermediario",
            $nIIntermediario,
            true,
            $identificador . "Número de identificação do Intermediário"
        );
        $this->dom->addChild(
            $info,
            "nomeIntermediario",
            $nome,
            true,
            $identificador . "Informar a razão social do Intermediário"
        );
        
        $infoadress = $this->dom->createElement("endereco");
        $this->dom->addChild(
            $infoadress,
            "enderecoLivre",
            $endereco,
            true,
            $identificador . "Endereço do Intermediário"
        );
        $this->dom->addChild(
            $infoadress,
            "pais",
            $pais,
            true,
            $identificador . "Sigla do pais"
        );
        $this->dom->appChild($info, $infoadress);
        
        $this->dom->addChild(
            $info,
            "paisResidencia",
            $paisResidencia,
            true,
            $identificador . "Sigla do pais residencia"
        );
        $this->info = $info;
        return $info;
    }
}
