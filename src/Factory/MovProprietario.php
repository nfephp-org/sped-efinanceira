<?php
namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Movimento Proprietario
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc\Factory\MovProprietario
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\MovDeclarado;

class MovProprietario extends MovDeclarado
{
    /**
     * Conjunto de proprietários
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProp;
    /**
     * Conjunto de NIF dos proprietários
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProprietarioNIF = array();
    /**
     * Conjunto de Peises de residencia do proprietario
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProprietarioPaisResid = array();
    /**
     * Conjunto de Peises de Nacionalidade do proprietario
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProprietarioPaisNac = array();
    /**
     * Conjunto de Peises de reportavies do proprietario
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProprietarioReportavel = array();
    
    /**
     * Faz a premontagem dos dados dos proprietários
     *
     * @return none
     */
    protected function premonta()
    {
        parent::premonta();
        if (empty($this->aProp)) {
            return;
        }
        //listar os numeros dos proprietários registrados
        $aPr = array_keys($this->aProp);
        foreach ($aPr as $num) {
            //verificar se existem NIF dos proprietarios
            if (array_key_exists($num, $this->aProprietarioNIF)) {
                foreach ($this->aProprietarioNIF[$num] as $nif) {
                    //adicionar os NIF do proprietário
                    $this->dom->appChildBefore($this->aProp[$num], $nif, 'Nome');
                }
            }
            //verificar se existem dados de residencia
            if (array_key_exists($num, $this->aProprietarioPaisResid)) {
                foreach ($this->aProprietarioPaisResid[$num] as $pais) {
                    $this->dom->appChild($this->aProp[$num], $pais);
                }
            }
            //verificar se existem dados de nacionalidade
            if (array_key_exists($num, $this->aProprietarioPaisNac)) {
                foreach ($this->aProprietarioPaisNac[$num] as $pais) {
                    $this->dom->appChild($this->aProp[$num], $pais);
                }
            }
            //verificar se existem dados de reportaveis
            if (array_key_exists($num, $this->aProprietarioReportavel)) {
                foreach ($this->aProprietarioReportavel[$num] as $pais) {
                    $this->dom->appChild($this->aProp[$num], $pais);
                }
            }
        }
        foreach ($this->aProp as $proprietario) {
            $this->dom->appChild($this->evt, $proprietario);
        }
    }

    /**
     * Cria as tags NIF do proprietario
     * podem existir ZERO ou mais
     *
     * @param  string $nIProprietario identificação do proprietário
     * @param  string $numeroNIF
     * @param  string $paisEmissao
     * @return Dom tag NIF
     */
    public function proprietarioNIF($nIProprietario, $numeroNIF, $paisEmissao)
    {
        $nif = $this->zNIF($numeroNIF, $paisEmissao);
        $this->aProprietarioNIF[$nIProprietario][] = $nif;
        return $nif;
    }
 
    /**
     * Crias as tags PaisResid do declarado
     * podem existir ZERO ou mais
     *
     * @param  string $nIProprietario identificação do proprietário
     * @param  string $pais
     * @return Dom tag PaisResid
     */
    public function proprietarioPaisResid($nIProprietario, $pais)
    {
        $tpais = $this->zPais($pais, 'PaisResid');
        $this->aProprietarioPaisResid[$nIProprietario][] = $tpais;
        return $tpais;
    }
    
    /**
     * Crias as tags PaisNacionalidade do declarado
     * podem existir ZERO ou mais
     *
     * @param  string $nIProprietario identificação do proprietário
     * @param  string $pais
     * @return Dom tag PaisResid
     */
    public function proprietarioPaisNac($nIProprietario, $pais)
    {
        $tpais = $this->zPais($pais, 'PaisNacionalidade');
        $this->aProprietarioPaisNac[$nIProprietario][] = $tpais;
        return $tpais;
    }
   
    /**
     * Cria o conjunto de paises reportaveis para o proprietario
     *
     * @param  string $nIProprietario identificação do proprietário
     * @param  string $pais
     * @return Dom tag Reportavel
     */
    public function proprietarioReportavel($nIProprietario, $pais)
    {
        $tpais = $this->zPais($pais, 'Reportavel');
        $this->aProprietarioReportavel[$nIProprietario][] = $tpais;
        return $tpais;
    }
    
    /**
     * Cria o conjunto de tag de proprietarios
     *
     * @param  string $tpNI
     * @param  string $nIProprietario
     * @param  string $nome
     * @param  string $dataNasc
     * @param  string $endereco
     * @param  string $pais
     * @return Dom tag proprietario
     */
    public function proprietario(
        $tpNI,
        $nIProprietario,
        $nome,
        $dataNasc,
        $endereco,
        $pais
    ) {
        $identificador = 'tag Proprietarios ';
        $proprietario = $this->dom->createElement("Proprietarios");
        $this->dom->addChild(
            $proprietario,
            "tpNI",
            $tpNI,
            true,
            $identificador . "tipo de NI "
        );
        $this->dom->addChild(
            $proprietario,
            "NIProprietario",
            $nIProprietario,
            true,
            $identificador . "NI do proprietário"
        );
        $this->dom->addChild(
            $proprietario,
            "Nome",
            $nome,
            true,
            $identificador . "Nome do Proprietário"
        );
        $this->dom->addChild(
            $proprietario,
            "EnderecoLivre",
            $endereco,
            false,
            $identificador . "Endereco Livre do Proprietario"
        );
        $gpais = $this->dom->createElement("PaisEndereco");
        $this->dom->addChild(
            $gpais,
            "Pais",
            $pais,
            true,
            $identificador . "Pais Endereco do Declarado"
        );
        $this->dom->appChild($proprietario, $gpais);
        $this->dom->addChild(
            $proprietario,
            "DataNasc",
            $dataNasc,
            false,
            $identificador . "Nome do Proprietário"
        );
        $this->aProp[$nIProprietario] = $proprietario;
        return $proprietario;
    }
}
