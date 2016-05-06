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
    public static function readReturn($method, $xmlResp = '')
    {
        if (trim($xmlResp) == '') {
            return [
                'bStat' => false,
                'message' => 'Não retornou nenhum dado'
            ];
        }
        libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($xmlResp);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        if (! empty($errors)) {
            return [
                'bStat' => false,
                'message' => $xmlResp
            ];
        }
        //foi retornado um xml continue
        $reason = self::checkForFault($dom);
        if ($reason != '') {
            return [
                'bStat' => false,
                'message' => $reason
            ];
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
            case 'ReceberLoteEvento':
                return self::readEnviarLoteEvento($dom);
                break;
        }
        return array();
    }
    
    /**
     * Verifica se o retorno é relativo a um ERRO SOAP
     *
     * @param DOMDocument $dom
     * @return string
     */
    public static function checkForFault($dom)
    {
        $tagfault = $dom->getElementsByTagName('Fault')->item(0);
        if (empty($tagfault)) {
            return '';
        }
        $tagreason = $tagfault->getElementsByTagName('Reason')->item(0);
        if (! empty($tagreason)) {
            $reason = $tagreason->getElementsByTagName('Text')->item(0)->nodeValue;
            return $reason;
        }
        return 'Houve uma falha na comunicação.';
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
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node)['cnpj'];
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        $aResposta['numeroRecibo'] = self::retValue($node, 'numeroRecibo');
        $aResposta['idEvento'] = self::retValue($node, 'id');
        //informaçṍes cadastrais
        $info = $node->getElementsByTagName('informacoesCadastrais')->item(0);
        if (!empty($info)) {
            $aInfo['cnpj'] = self::retValue($info, 'cnpj');
            $aInfo['giin'] = self::retValue($info, 'giin');
            $aInfo['nome'] =  self::retValue($info, 'nome');
            $aInfo['endereco'] =  self::retValue($info, 'endereco');
            $aInfo['municipio'] =  self::retValue($info, 'municipio');
            $aInfo['uf'] =  self::retValue($info, 'uf');
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
            'dataHoraProcessamento' => '',
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
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node)['cnpj'];
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('informacoesEFinanceira');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $node = $info->item($n);
                $aInfo['dhInicial'] = self::retValue($node, 'dhInicial');
                $aInfo['dhFinal'] = self::retValue($node, 'dhFinal');
                $aInfo['situacaoEFinanceira'] = self::retValue($node, 'situacaoEFinanceira', '0');
                $aInfo['numeroReciboAbertura'] = self::retValue($node, 'numeroReciboAbertura');
                $aInfo['idAbertura'] = self::retValue($node, 'idAbertura');
                $aInfo['numeroReciboFechamento'] = self::retValue($node, 'numeroReciboFechamento');
                $aInfo['idFechamento'] = self::retValue($node, 'idFechamento');
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
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node)['cnpj'];
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('informacoesMovimento');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $node = $info->item($n);
                $aInfo['tipoMovimento'] = self::retValue($node, 'tipoMovimento');
                $aInfo['tipoNI'] = self::retValue($node, 'tipoNI');
                $aInfo['NI'] = self::retValue($node, 'NI');
                $aInfo['anoMesCaixa'] = self::retValue($node, 'anoMesCaixa');
                $aInfo['situacao'] = self::retValue($node, 'situacao');
                $aInfo['numeroRecibo'] = self::retValue($node, 'numeroRecibo');
                $aInfo['id'] = self::retValue($node, 'id');
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
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node)['cnpj'];
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('identificacaoIntermediario');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $node = $info->item($n);
                $aInfo['GIIN'] = self::retValue($node, 'GIIN');
                $aInfo['tpNI'] = self::retValue($node, 'tpNI');
                $aInfo['NIIntermediario'] = self::retValue($node, 'NIIntermediario');
                $aInfo['numeroRecibo'] = self::retValue($node, 'numeroRecibo');
                $aInfo['id'] = self::retValue($node, 'id');
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
            'giinEmpresaDeclarante' => '',
            'dataHoraProcessamento' => '',
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
        $aResposta['cnpjEmpresaDeclarante'] = self::retDeclarante($node)['cnpj'];
        $aResposta['giinEmpresaDeclarante'] = self::retDeclarante($node)['giin'];
        //busca o status
        $aResposta['status'] = self::retStatus($node);
        //informacoesEFinanceira
        $info = $node->getElementsByTagName('identificacaoPatrocinado');
        if (isset($info)) {
            $n = 0;
            foreach ($info as $inform) {
                $node = $info->item($n);
                $aInfo['GIIN'] = self::retValue($node, 'GIIN');
                $aInfo['CNPJ'] = self::retValue($node, 'CNPJ');
                $aInfo['numeroRecibo'] = self::retValue($node, 'numeroRecibo');
                $aInfo['id'] = self::retValue($node, 'id');
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
        $aResposta = [
            'bStat' => false,
            'IdTransmissor' => '',
            'status' => array(),
            'retornoEventos' => array()
        ];
        $aEventos = [];
        $tag = $dom->getElementsByTagName('retornoLoteEventos')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta['bStat'] = true;
        //busca o status
        $aResposta['status'] = self::retStatus($tag);
        $tag = $dom->getElementsByTagName('retornoEventos')->item(0);
        $eventos = $tag->getElementsByTagName('evento');
        $i = 0;
        foreach ($eventos as $evento) {
            $ret = $eventos->item($i)->getElementsByTagName('retornoEvento')->item(0);
            $recepcao = $ret->getElementsByTagName('dadosRecepcaoEvento')->item(0);
            $dadosReciboEntrega = $ret->getElementsByTagName('dadosReciboEntrega')->item(0);
            $aEvento['id'] = $ret->getAttribute('id');
            $aEvento['cnpjDeclarante'] = self::retDeclarante($ret)['cnpj'];
            $aEvento['dhProcessamento'] = self::retValue($recepcao, 'dhProcessamento');
            $aEvento['tipoEvento'] = self::retValue($recepcao, 'tipoEvento');
            $aEvento['idEvento'] = self::retValue($recepcao, 'idEvento');
            $aEvento['hash'] = self::retValue($recepcao, 'hash');
            $aEvento['nrRecibo'] = self::retValue($recepcao, 'nrRecibo');
            $aEvento['status'] = self::retStatus($ret);
            $aEvento['numeroRecibo'] = '';
            $aEvento['numeroRecibo'] = self::retValue($dadosReciboEntrega, 'numeroRecibo');
            $aEventos[] = $aEvento;
            $i++;
        }
        $aResposta['retornoEventos'] = $aEventos;
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
        $dtHora = self::retValue($node, 'dataHoraProcessamento');
        if (empty($dtHora)) {
            $dtHora = self::retValue($node, 'dhProcessamento');
        }
        if (! empty($dtHora)) {
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
        $cnpj = self::retValue($nodeDeclarante, 'cnpjEmpresaDeclarante');
        $giin = self::retValue($nodeDeclarante, 'GIIN');
        return array('cnpj'=>$cnpj, "giin"=>$giin);
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
        $aStatus['cdRetorno'] = self::retValue($nodestatus, 'cdRetorno', '0');
        $aStatus['descRetorno'] = self::retValue($nodestatus, 'descRetorno');
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
                        $dnode = $nodeocor->item($x);
                        $aOcorr['tipo'] = self::retValue($dnode, 'tipo');
                        $aOcorr['codigo'] = self::retValue($dnode, 'codigo');
                        $aOcorr['descricao'] = self::retValue($dnode, 'descricao');
                        $aOcorr['localizacaoErroAviso'] = self::retValue($dnode, 'localizacaoErroAviso');
                    }
                    $aOcorrencias[] = $aOcorr;
                }
                $n++;
            }
        }
        $aStatus['ocorrencias'] = $aOcorrencias;
        return $aStatus;
    }
    
    /**
     * Extrai o valor de uma tag
     *
     * @param DOMElement $node
     * @param string $tag
     * @param string $expected
     * @return string
     */
    private static function retValue($node, $tag, $expected = '')
    {
        if (empty($node) || empty($tag)) {
            return '';
        }
        return !empty($node->getElementsByTagName($tag)->item(0)->nodeValue) ?
            $node->getElementsByTagName($tag)->item(0)->nodeValue :
            $expected;
    }
}
