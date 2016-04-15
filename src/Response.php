<?php

namespace NFePHP\eFinanc;

/**
 * Classe auxiliar que le e trata todos os retornos do webservice
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use DOMDocument;

class Response
{
    public static function readReturn($method, $xmlResp)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($xmlResp);
        if ($reason = self::checkForFault($dom) != '') {
            return array('Fault' => $reason);
        }
        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'ConsultarInformacoesCadastrais':
                return self::readConsultarInformacoesCadastrais($dom);
                break;
            case 'ConsultarListaEFinanceira':
                return self::readConsultarListaEFinanceira($dom);
                break;
            case 'ConsultarInformacoesMovimento':
                return self::readConsultarInformacoesMovimento($dom);
                break;
            case 'ConsultarInformacoesIntermediario':
                return self::readConsultarInformacoesIntermediario($dom);
                break;
            case 'ConsultarInformacoesPatrocinado':
                return self::readConsultarInformacoesPatrocinado($dom);
                break;
            case 'EnviarLoteEvento':
                return self::readEnviarLoteEvento($dom);
                break;
        }
        return array();
    }
    
    /**
     * Verifica se o retorno é relativo a um ERRO
     *
     * @param Dom $dom
     * @return string
     */
    public static function checkForFault($dom)
    {
        return '';
    }
    
    /**
     * Lê o retorno da Consulta Informacoes Cadastrais do Declarante
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesCadastrais($dom)
    {
        $aResposta = [
            'bStat' => false,
            'cnpjEmpresaDeclarante' => '',
            'dataHoraProcessamento' => '',
            'cdRetorno' => '',
            'descRetorno' => '',
            'tipo' => '',
            'codigo' => '',
            'descricao' => ''
        ];
        $tag = $dom->getNode('ConsultarInformacoesCadastraisResponse', 0);
        if (! isset($tag)) {
            return $aResposta;
        }
        //indica que houve resposta do webservice se for true
        //caso seja false algo estranho ocorreu
        $aResposta['bStat'] = true;
        //nó principal
        $node = $dom->getElementsByTagName('retornoConsultaInformacoesCadastrais')->item(0);
        //declarante
        $identificacaoDeclarante = $node->getElementsByTagName('identificacaoEmpresaDeclarante')->item(0);
        $aResposta['cnpjEmpresaDeclarante'] = $dom->getNodeValue('cnpjEmpresaDeclarante', $identificacaoDeclarante);
        $aResposta['dataHoraProcessamento'] = $dom->getNodeValue('dataHoraProcessamento', $node);
        $nodestatus = $dom->getNode('status', 0);
        $aResposta['cdRetorno'] = $dom->getNodeValue('cdRetorno', $nodestatus);
        $aResposta['descRetorno'] = $dom->getNodeValue('descRetorno', $nodestatus);
        $nodedados = $dom->getNode('dadosRegistroOcorrenciaEvento', 0);
        $aResposta['tipo'] = $dom->getNodeValue('tipo', $nodedados);
        $aResposta['codigo'] = $dom->getNodeValue('codigo', $nodedados);
        $aResposta['descricao'] = $dom->getNodeValue('descricao', $nodedados);
        $aResposta['localizacaoErroAviso'] = $dom->getNodeValue('localizacaoErroAviso', $nodedados);
        return $aResposta;
    }
    
    /**
     * Lê o retorno da Consulta a Lista e-Financeira
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarListaEFinanceira($dom)
    {
        return array();
    }
    
    /**
     * Lê o retorno da Consulta Informacoes de Movimento
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesMovimento($dom)
    {
        return array();
    }
    
    /**
     * Lê o retorno da Consulta Informacoes do Intemedirário
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesIntermediario($dom)
    {
        return array();
    }

    /**
     * Lê o retorno da Consulta Informacoes do Patrocinado
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesPatrocinado($dom)
    {
        return array();
    }
    
    /**
     * Lê o retorno do envio de lote
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readEnviarLoteEvento($dom)
    {
        $aResposta = [];
        $tag = $dom->getElementsByTagName('retornoLoteEventos')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        //$id = $tag->get
        return $aResposta;
    }
}
