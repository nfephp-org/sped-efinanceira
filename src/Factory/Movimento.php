<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Movimento
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\Movimento
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class Movimento extends Factory
{
    /**
     * Conjunto de proprietários
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProp;

    /**
     * Conjunto de movimentos
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aMov;
    
    /**
     * Objeto Dom::class Tag ideDeclarado
     * @var Dom
     */
    protected $ideDeclarado;

    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtMovOpFin';

    protected function premonta()
    {
        return;
    }

    public function ideDeclarado(
        $tpNI,
        $tpDeclarado,
        $nIDeclarado,
        $nIIF,
        $nomeDeclarado,
        $dataNasc,
        $enderecoLivre,
        $pais,
        $paisResid,
        $paisNacionalidade
    ) {
        
    }
    

    public function proprietarios(
        $tpNI,
        $nIProprietario,
        $nIIF,
        $nome,
        $dataNasc,
        $endereco,
        $pais,
        $paisResid,
        $paisNacionalidade,
        $reportavel
    ) {
        
    }

    public function movFin(
        $anomes,
        $tpConta,
        $subTpConta,
        $tpNumConta,
        $numConta,
        $tpRelacaoDeclarado,
        $noTitulares,
        $totCreditos,
        $totDebitos,
        $totCreditosMesmaTitularidade,
        $totDebitosMesmaTitularidade,
        $tpPgto,
        $totPgtosAcum,
        $totCompras,
        $totVendas,
        $totTransferencias
    ) {
        
    }
}
