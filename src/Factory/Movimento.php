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
        //1|CPF
        //2|CNPJ
        //3|NIF Pessoa Fisica
        //4|NIF Pessoa Jurddica
        //5|Passaporte
        //6|Numero do PIS
        //7|Identidade Mercosul
        //99|Sem NI
        $tpDeclarado,
        $nIDeclarado,
        $nomeDeclarado,
        $enderecoLivre,
        $pais
    ) {
        
    }
    
    public function nifDeclarado($numeroNIF, $paisEmissaoNIF)
    {
        
    }

    public function proprietarios(
        $tpNI,
        $nIProprietario,
        $numeroNIF,
        $paisEmissaoNIF,
        $nome,
        $enderecoLivre,
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
