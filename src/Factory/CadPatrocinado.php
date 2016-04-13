<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Cadastro do Patrocinado
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc\Factory\CadPatrocinado
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class CadPatrocinado extends Factory
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
    protected $signTag = 'evtCadPatrocinado';
   
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
     * @param  string $nome
     * @param  string $endereco
     * @param  string $municipio
     * @param  string $uf
     * @param  string $pais
     * @param  string $paisResidencia
     * @return Dom
     */
    public function tagInfo($giin, $cnpj, $nome, $endereco, $municipio, $pais, $paisResidencia)
    {
        $identificador = 'tag infoPatrocinado ';
        $info = $this->dom->createElement("infoPatrocinado");
        $this->dom->addChild(
            $info,
            "GIIN",
            $giin,
            true,
            $identificador . ""
        );
        $this->dom->addChild(
            $info,
            "CNPJ",
            $cnpj,
            true,
            $identificador . ""
        );
        $this->dom->addChild(
            $info,
            "nomePatrocinado",
            $nome,
            true,
            $identificador . "Nome do patrocinado"
        );
        
        $infoadress = $this->dom->createElement("endereco");
        $this->dom->addChild(
            $infoadress,
            "enderecoLivre",
            $endereco,
            true,
            $identificador . "Endereco do patrocinado"
        );
        $this->dom->addChild(
            $infoadress,
            "municipio",
            $municipio,
            true,
            $identificador . "Municipio do patrocinado"
        );
        $this->dom->addChild(
            $infoadress,
            "pais",
            $pais,
            true,
            $identificador . "Sigla do pais do Patrocinado"
        );
        $this->dom->appChild($info, $infoadress);
        
        $this->dom->addChild(
            $info,
            "paisResidencia",
            $paisResidencia,
            true,
            $identificador . "Sigla do pais residencia do Patrocinado"
        );
        $this->info = $info;
        return $info;
    }
}
