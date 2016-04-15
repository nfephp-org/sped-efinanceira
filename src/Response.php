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
use NFePHP\Common\DateTime\DateTime;

class Response
{
    /**
     * Verifica o retorno e extrai os seus dados conforme o método usado
     * na consulta ao webservice ou no retorno de envio de lote
     *
     * @param string $method
     * @param string $xmlResp
     * @return array
     */
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
        $aStatus = [
            'cdRetorno' => '',
            'descRetorno' => '',
            'ocorrencias' => array()
        ];
        $aInfo = [
            'cnpj'=> '',
            'giin'=> '',
            'nome' => '',
            'endereco' => '',
            'municipio' => '',
            'uf' => ''
        ];
        $aResposta = [
            'bStat' => false,
            'cnpjEmpresaDeclarante' => '',
            'dataHoraProcessamento' => '',
            'status' => array(),
            'informacoesCadastrais' => array(),
            'numeroRecibo' => '',
            'idEvento' => ''
        ];
        $tag = $dom->getElementsByTagName('ConsultarInformacoesCadastraisResponse')->item(0);
        if (!isset($tag)) {
            return $aResposta;
        }
        //indica que houve resposta do webservice se for true
        //caso seja false algo estranho ocorreu
        $aResposta['bStat'] = true;
        //nó principal 1 - 1
        $node = $tag->getElementsByTagName('retornoConsultaInformacoesCadastrais')->item(0);
        //busca a data e hora do processamento
        $aResposta['dataHoraProcessamento'] = self::retDataHora($node);
        //busca dados declarante
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node);
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        $aResposta['numeroRecibo'] =
            !empty($node->getElementsByTagName('numeroRecibo')->item(0)->nodeValue) ?
            $node->getElementsByTagName('numeroRecibo')->item(0)->nodeValue :
            '';
        $aResposta['idEvento'] =
            !empty($node->getElementsByTagName('id')->item(0)->nodeValue) ?
            $node->getElementsByTagName('id')->item(0)->nodeValue :
            '';
        //informaçṍes cadastrais
        $info = $node->getElementsByTagName('informacoesCadastrais')->item(0);
        if (!empty($info)) {
            $aInfo['cnpj'] = $info->getElementsByTagName('cnpj')->item(0)->nodeValue;
            $aInfo['giin'] = $info->getElementsByTagName('giin')->item(0)->nodeValue;
            $aInfo['nome'] = $info->getElementsByTagName('nome')->item(0)->nodeValue;
            $aInfo['endereco'] = $info->getElementsByTagName('endereco')->item(0)->nodeValue;
            $aInfo['municipio'] = $info->getElementsByTagName('municipio')->item(0)->nodeValue;
            $aInfo['uf'] = $info->getElementsByTagName('uf')->item(0)->nodeValue;
        }
        $aResposta['informacoesCadastrais'] = $aInfo;
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
        $aResposta = [
            'bStat' => false,
            'cnpjEmpresaDeclarante' => '',
            'dhProcessamento' => '',
            'status' => array(),
            'informacoesEFinanceira' => array()
        ];
        $aInfos = array();
        $aInfo = [
            'dhInicial'=>'',
            'dhFinal'=>'',
            'situacaoEFinanceira'=>'',
            'numeroReciboAbertura'=>'',
            'idAbertura'=>'',
            'numeroReciboFechamento'=>'',
            'idFechamento'=>''
        ];
        $tag = $dom->getElementsByTagName('ConsultarListaEFinanceiraResponse')->item(0);
        if (!isset($tag)) {
            return $aResposta;
        }
        //indica que houve resposta do webservice se for true
        //caso seja false algo estranho ocorreu
        $aResposta['bStat'] = true;
        //nó principal 1 - 1
        $node = $tag->getElementsByTagName('retornoConsultaListaEFinanceira')->item(0);
        //busca a data e hora do processamento
        $aResposta['dataHoraProcessamento'] = self::retDataHora($node);
        //busca dados declarante
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node);
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('informacoesEFinanceira');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $aInfo['dhInicial'] =
                    !empty($info->item($n)->getElementsByTagName('dhInicial')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('dhInicial')->item(0)->nodeValue :
                    '';
                $aInfo['dhFinal'] =
                    !empty($info->item($n)->getElementsByTagName('dhFinal')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('dhFinal')->item(0)->nodeValue :
                    '';
                $aInfo['situacaoEFinanceira'] =
                    !empty($info->item($n)->getElementsByTagName('situacaoEFinanceira')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('situacaoEFinanceira')->item(0)->nodeValue :
                    '';
                $aInfo['numeroReciboAbertura'] =
                    !empty($info->item($n)->getElementsByTagName('numeroReciboAbertura')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('numeroReciboAbertura')->item(0)->nodeValue :
                    '';
                $aInfo['idAbertura'] =
                    !empty($info->item($n)->getElementsByTagName('idAbertura')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('idAbertura')->item(0)->nodeValue :
                    '';
                $aInfo['numeroReciboFechamento'] =
                    !empty($info->item($n)->getElementsByTagName('numeroReciboFechamento')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('numeroReciboFechamento')->item(0)->nodeValue :
                    '';
                $aInfo['idFechamento'] =
                    !empty($info->item($n)->getElementsByTagName('idFechamento')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('idFechamento')->item(0)->nodeValue :
                    '';
                $aInfos[] = $aInfo;
                $n++;
            }
        }
        $aResposta['informacoesEFinanceira'] = $aInfos;
        return $aResposta;
    }
    
    /**
     * Lê o retorno da Consulta Informacoes de Movimento
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesMovimento($dom)
    {
        $aResposta = [
            'bStat' => false,
            'cnpjEmpresaDeclarante' => '',
            'dhProcessamento' => '',
            'status' => array(),
            'informacoesMovimento' => array()
        ];
        $aInfos = array();
        $aInfo = [
            'tipoMovimento'=>'',
            'tipoNI'=>'',
            'NI'=>'',
            'anoMesCaixa'=>'',
            'situacao'=>'',
            'numeroRecibo'=>'',
            'id'=>''
        ];
        $tag = $dom->getElementsByTagName('ConsultarInformacoesMovimentoResponse')->item(0);
        if (!isset($tag)) {
            return $aResposta;
        }
        //indica que houve resposta do webservice se for true
        //caso seja false algo estranho ocorreu
        $aResposta['bStat'] = true;
        //nó principal 1 - 1
        $node = $tag->getElementsByTagName('retornoConsultaInformacoesMovimento')->item(0);
        //busca a data e hora do processamento
        $aResposta['dataHoraProcessamento'] = self::retDataHora($node);
        //busca dados declarante
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node);
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('informacoesMovimento');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $aInfo['tipoMovimento'] =
                    !empty($info->item($n)->getElementsByTagName('tipoMovimento')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('tipoMovimento')->item(0)->nodeValue :
                    '';
                $aInfo['tipoNI'] =
                    !empty($info->item($n)->getElementsByTagName('tipoNI')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('tipoNI')->item(0)->nodeValue :
                    '';
                $aInfo['NI'] =
                    !empty($info->item($n)->getElementsByTagName('NI')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('NI')->item(0)->nodeValue :
                    '';
                $aInfo['anoMesCaixa'] =
                    !empty($info->item($n)->getElementsByTagName('anoMesCaixa')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('anoMesCaixa')->item(0)->nodeValue :
                    '';
                $aInfo['situacao'] =
                    !empty($info->item($n)->getElementsByTagName('situacao')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('situacao')->item(0)->nodeValue :
                    '';
                $aInfo['numeroRecibo'] =
                    !empty($info->item($n)->getElementsByTagName('numeroRecibo')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('numeroRecibo')->item(0)->nodeValue :
                    '';
                $aInfo['id'] =
                    !empty($info->item($n)->getElementsByTagName('id')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('id')->item(0)->nodeValue :
                    '';
                $aInfos[] = $aInfo;
                $n++;
            }
        }
        $aResposta['informacoesMovimento'] = $aInfos;
        return $aResposta;
    }
    
    /**
     * Lê o retorno da Consulta Informacoes do Intemedirário
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesIntermediario($dom)
    {
        $aResposta = [
            'bStat' => false,
            'cnpjEmpresaDeclarante' => '',
            'dhProcessamento' => '',
            'status' => array(),
            'identificacaoIntermediario' => array()
        ];
        $aInfos = array();
        $aInfo = [
            'GIIN'=>'',
            'tpNI'=>'',
            'NIIntermediario'=>'',
            'numeroRecibo'=>'',
            'id'=>''
        ];
        $tag = $dom->getElementsByTagName('ConsultarInformacoesIntermediarioResponse')->item(0);
        if (!isset($tag)) {
            return $aResposta;
        }
        //indica que houve resposta do webservice se for true
        //caso seja false algo estranho ocorreu
        $aResposta['bStat'] = true;
        //nó principal 1 - 1
        $node = $tag->getElementsByTagName('retornoConsultaInformacoesIntermediario')->item(0);
        //busca a data e hora do processamento
        $aResposta['dataHoraProcessamento'] = self::retDataHora($node);
        //busca dados declarante
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node);
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('identificacaoIntermediario');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $aInfo['GIIN'] =
                    !empty($info->item($n)->getElementsByTagName('GIIN')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('GIIN')->item(0)->nodeValue :
                    '';
                $aInfo['tpNI'] =
                    !empty($info->item($n)->getElementsByTagName('tpNI')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('tpNI')->item(0)->nodeValue :
                    '';
                $aInfo['NIIntermediario'] =
                    !empty($info->item($n)->getElementsByTagName('NIIntermediario')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('NIIntermediario')->item(0)->nodeValue :
                    '';
                $aInfo['numeroRecibo'] =
                    !empty($info->item($n)->getElementsByTagName('numeroRecibo')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('numeroRecibo')->item(0)->nodeValue :
                    '';
                $aInfo['id'] =
                    !empty($info->item($n)->getElementsByTagName('id')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('id')->item(0)->nodeValue :
                    '';
                $aInfos[] = $aInfo;
                $n++;
            }
        }
         $aResposta['identificacaoIntermediario'] = $aInfos;
        return $aResposta;
    }

    /**
     * Lê o retorno da Consulta Informacoes do Patrocinado
     *
     * @param DOMDocument $dom
     * @return array
     */
    public static function readConsultarInformacoesPatrocinado($dom)
    {
        $aResposta = [
            'bStat' => false,
            'cnpjEmpresaDeclarante' => '',
            'dhProcessamento' => '',
            'status' => array(),
            'identificacaoPatrocinado' => array()
        ];
        $aInfos = array();
        $aInfo = [
            'GIIN'=>'',
            'CNPJ'=>'',
            'numeroRecibo'=>'',
            'id'=>''
        ];
        $tag = $dom->getElementsByTagName('ConsultarInformacoesPatrocinadoResponse')->item(0);
        if (!isset($tag)) {
            return $aResposta;
        }
        //indica que houve resposta do webservice se for true
        //caso seja false algo estranho ocorreu
        $aResposta['bStat'] = true;
        //nó principal 1 - 1
        $node = $tag->getElementsByTagName('retornoConsultaInformacoesPatrocinado')->item(0);
        //busca a data e hora do processamento
        $aResposta['dataHoraProcessamento'] = self::retDataHora($node);
        //busca dados declarante
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node);
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('identificacaoPatrocinado');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $aInfo['GIIN'] =
                    !empty($info->item($n)->getElementsByTagName('GIIN')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('GIIN')->item(0)->nodeValue :
                    '';
                $aInfo['CNPJ'] =
                    !empty($info->item($n)->getElementsByTagName('CNPJ')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('CNPJ')->item(0)->nodeValue :
                    '';
                $aInfo['numeroRecibo'] =
                    !empty($info->item($n)->getElementsByTagName('numeroRecibo')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('numeroRecibo')->item(0)->nodeValue :
                    '';
                $aInfo['id'] =
                    !empty($info->item($n)->getElementsByTagName('id')->item(0)->nodeValue) ?
                    $info->item($n)->getElementsByTagName('id')->item(0)->nodeValue :
                    '';
                $aInfos[] = $aInfo;
                $n++;
            }
        }
         $aResposta['identificacaoPatrocinado'] = $aInfos;
        return $aResposta;
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
        return $aResposta;
    }
    
    /**
     * Busca a data e hora do processamento
     *
     * @param DOMElement $node
     * @return strinf
     */
    protected static function retDataHora($node)
    {
        $data = '';
        $dtHora = $node->getElementsByTagName('dataHoraProcessamento')->item(0)->nodeValue;
        if (!isset($dtHora)) {
            $dtHora = $node->getElementsByTagName('dhProcessamento')->item(0)->nodeValue;
        }
        if (isset($dtHora)) {
            $data = date('d/m/Y H:i:s', DateTime::convertSefazTimeToTimestamp($dtHora));
        }
        return $data;
    }

    /**
     * Busca o cnpj do declarante
     *
     * @param DOMElement $node
     * @return string
     */
    protected static function retDeclarante($node)
    {
        //declarante 1 - 1
        $nodeDeclarante = $node->getElementsByTagName('identificacaoEmpresaDeclarante')->item(0);
        $cnpj = $nodeDeclarante->getElementsByTagName('cnpjEmpresaDeclarante')->item(0)->nodeValue;
        return $cnpj;
    }
    
    /**
     * Busca e retorna o status em um array
     *
     * @param DOMElement $node
     * @return array
     */
    protected static function retStatus($node)
    {
        $aOcorrencias = array();
        $aOcorr = [
            'tipo' => '',
            'codigo' => '',
            'descricao' => '',
            'localizacaoErroAviso' => ''
        ];
        $aStatus = [
            'cdRetorno' => '',
            'descRetorno' => '',
            'ocorrencias' => array()
        ];
        //status 1 - 1
        $nodestatus = $node->getElementsByTagName('status')->item(0);
        $aStatus['cdRetorno'] = $nodestatus->getElementsByTagName('cdRetorno')->item(0)->nodeValue;
        $aStatus['descRetorno'] = $nodestatus->getElementsByTagName('descRetorno')->item(0)->nodeValue;
        //status/dadosRegistroOcorrenciaEvento 0 -> N
        $nodedados = $nodestatus->getElementsByTagName('dadosRegistroOcorrenciaEvento');
        if (isset($nodedados)) {
            $n = 0;
            foreach ($nodedados as $dados) {
                //dadosRegistroOcorrenciaEvento/ocorrencias 0 - N
                $nodeocor = $nodedados->item($n)->getElementsByTagName('ocorrencias');
                if (isset($nodeocor)) {
                    $x = 0;
                    foreach ($nodeocor as $ocor) {
                        $aOcorr['tipo'] =
                            !empty($nodeocor->item($x)->getElementsByTagName('tipo')->item(0)->nodeValue) ?
                            $nodeocor->item($x)->getElementsByTagName('tipo')->item(0)->nodeValue :
                            '';
                        $aOcorr['codigo'] =
                            !empty($nodeocor->item($x)->getElementsByTagName('codigo')->item(0)->nodeValue) ?
                            $nodeocor->item($x)->getElementsByTagName('codigo')->item(0)->nodeValue :
                            '' ;
                        $aOcorr['descricao'] =
                            !empty($nodeocor->item($x)->getElementsByTagName('descricao')->item(0)->nodeValue) ?
                            $nodeocor->item($x)->getElementsByTagName('descricao')->item(0)->nodeValue :
                            '';
                        $aOcorr['localizacaoErroAviso'] =
                            !empty($nodeocor->item($x)->getElementsByTagName('localizacaoErroAviso')->item(0)->nodeValue) ?
                            $nodeocor->item($x)->getElementsByTagName('localizacaoErroAviso')->item(0)->nodeValue :
                            '';
                    }
                    $aOcorrencias[] = $aOcorr;
                }
                $n++;
            }
        }
        $aStatus['ocorrencias'] = $aOcorrencias;
        return $aStatus;
    }
}
