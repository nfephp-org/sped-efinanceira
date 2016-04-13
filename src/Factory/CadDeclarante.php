<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Cadastro do Declarante
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\CadDeclarante
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class CadDeclarante extends Factory
{

    /**
     * Objeto Dom::class Tag infoCadastro
     * @var Dom
     */
    protected $info;
    
    
    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtCadDeclarante';
   
    /**
     * Faz a premontagem se necessário
     * @return
     */
    protected function premonta()
    {
        return;
    }
    
    /**
     * Cria a tag InfoCadastro
     *
     * @param string $giin
     * @param string $nome
     * @param string $endereco
     * @param string $municipio
     * @param string $uf
     * @param string $pais
     * @param string $paisResidencia
     * @return Dom
     */
    public function tagInfo($giin, $nome, $endereco, $municipio, $uf, $pais, $paisResidencia)
    {
        $identificador = 'tag infoCadastro ';
        $info = $this->dom->createElement("infoCadastro");
        $this->dom->addChild(
            $info,
            "GIIN",
            $giin,
            false,
            $identificador . "GIIN Declarante"
        );
        $this->dom->addChild(
            $info,
            "nome",
            $nome,
            true,
            $identificador . "Nome da Empresa Declarante"
        );
        $this->dom->addChild(
            $info,
            "enderecoLivre",
            $endereco,
            true,
            $identificador . "Endereco da Empresa Declarante"
        );
        $this->dom->addChild(
            $info,
            "municipio",
            $municipio,
            true,
            $identificador . "Municipio da Empresa Declarante"
        );
        $this->dom->addChild(
            $info,
            "UF",
            $uf,
            true,
            $identificador . "UF da Empresa Declarante"
        );
        $this->dom->addChild(
            $info,
            "Pais",
            $pais,
            true,
            $identificador . "Sigla do pais da Empresa Declarante"
        );
        $this->dom->addChild(
            $info,
            "paisResidencia",
            $paisResidencia,
            true,
            $identificador . "Sigla do pais residencia da Empresa Declarante"
        );
        $this->info = $info;
        return $info;
    }
}
