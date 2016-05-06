<?php

namespace NFePHP\eFinanc;

/**
 * Classe Tools, executa a comunicação com o webservice do e-Financeira
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc\Factory\Tools
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\BaseTools;

class Tools extends BaseTools
{
    /**
     * URL dos webservices
     * @var array
     */
    public $url = [
        1 => [
            'recepcao'=>'https://efinanc.receita.fazenda.gov.br/WsEFinanceira/WsRecepcao.asmx',
            'consulta'=>'https://efinanc.receita.fazenda.gov.br/WsEFinanceira/WsConsulta.asmx'
        ],
        2 => [
            'recepcao'=>'https://preprod-efinanc.receita.fazenda.gov.br/WsEFinanceira/WsRecepcao.asmx',
            'consulta'=>'https://preprod-efinanc.receita.fazenda.gov.br/WsEFinanceira/WsConsulta.asmx'
        ]
    ];
    
    /**
     * Construtor da classe
     * @param type $config config json
     * @param bool $ignore usado apenas para testes
     */
    public function __construct($config = '', $ignore = false)
    {
        parent::__construct($config, $ignore);
    }
       
    /**
     * Consulta informações cadastrais do Declarante
     *
     * @param  string $cnpj
     * @param  array  $aResp variável passada como referencia irá conter
     *                       os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     * @throws InvalidArgumentException
     */
    public function consultarInformacoesCadastrais($cnpj = '', &$aResp = array())
    {
        if ($cnpj == '') {
            $msg = 'O CNPJ do declarante deve ser passado.';
            throw new InvalidArgumentException($msg);
        }
        $urlService = $this->url[$this->tpAmb]['consulta'];
        $method = 'ConsultarInformacoesCadastrais';
        $body = "<ConsultarInformacoesCadastrais xmlns=\"$this->xmlns\">"
                . "<cnpj>$cnpj</cnpj>"
                . "</ConsultarInformacoesCadastrais>";
        $aRet = $this->zSend($urlService, $body, $method);
        $lastMsg = $aRet['lastMsg'];
        $retorno = $aRet['retorno'];
        $this->soapDebug = $aRet['soapDebug'];
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaCad.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        $filename = "$mark-retConsultaCad.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * Consulta Lista EFinanceira
     *
     * @param  string $cnpj CNPJ do Declarante
     * @param  string $sit Situacao EFinanceira 0 – Todas
     *                                          1 – Em Andamento
     *                                          2 – Ativa
     *                                          3 - Retificada
     *                                          4 - Excluída
     * @param  string $dtInicio     data valida formato ???
     * @param  string $dtFim        data valida formato ???
     * @param  array  $aResp variável passada como referencia irá conter
     *                       os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarListaEFinanceira(
        $cnpj = '',
        $sit = '0',
        $dtInicio = '',
        $dtFim = '',
        &$aResp = array()
    ) {
        if ($cnpj == '' || $sit == '') {
            $msg = 'Os primeiros dois parametros são obrigatórios.';
            throw new InvalidArgumentException($msg);
        }
        $urlService = $this->url[$this->tpAmb]['consulta'];
        $method = 'ConsultarListaEFinanceira';
        $body = "<ConsultarListaEFinanceira xmlns=\"$this->xmlns\">";
        $body .= "<cnpj>$cnpj</cnpj>";
        $body .= "<situacaoEFinanceira>$sit</situacaoEFinanceira>";
        if ($dtInicio != '') {
            $body .= "<dataInicio>$dtInicio</dataInicio>";
        }
        if ($dtFim != '') {
            $body .= "<dataFim>$dtFim</dataFim>";
        }
        $body .= "</ConsultarListaEFinanceira>";
        $aRet = $this->zSend($urlService, $body, $method);
        $lastMsg = $aRet['lastMsg'];
        $retorno = $aRet['retorno'];
        $this->soapDebug = $aRet['soapDebug'];
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaLista.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        $filename = "$mark-retConsultaLista.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * Consulta Informações de Movimento
     *
     * @param  string $cnpj CNPJ da empresa declarante
     * @param  string $sit  Situacao    0 - Todas
     *                                  1 - Em Andamento
     *                                  2 - Ativa
     *                                  3 - Retificada
     *                                  4 - Excluída
     * @param  string $anomesIni Ano/Mês inicial das informações AAAA/MM
     * @param  string $anomesFim Ano/Mês final das informações AAAA/MM
     * @param  string $tpmov Tipo de Movimento 1 - Previdência Privada
     *                                         2 - Operações Financeiras
     * @param  string $tpni Identificação do Declarado - Tipo de NI
     *         1 - CPF
     *         2 - CNPJ
     *         3 - NIF Pessoa Física (Número de Identificação Fiscal Pessoa Física)
     *         4 - NIF Pessoa Jurídica (Número de Identificação Fiscal Pessoa Jurídica)
     *         5 - Passaporte
     * @param  string $numni Valor conforme tipo de Identificação do declarado.
     * @param  array  $aResp variável passada como referencia irá conter
     *                       os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesMovimento(
        $cnpj = '',
        $sit = '',
        $anomesIni = '',
        $anomesFim = '',
        $tpmov = '',
        $tpni = '',
        $numni = '',
        &$aResp = array()
    ) {
        if ($cnpj == '' || $sit == '' || $anomesIni == '' || $anomesFim == '') {
            $msg = 'Os primeiros quatro parametros são obrigatórios.';
            throw new InvalidArgumentException($msg);
        }
        $urlService = $this->url[$this->tpAmb]['consulta'];
        $method = 'ConsultarInformacoesMovimento';
        $body = "<ConsultarInformacoesMovimento xmlns=\"$this->xmlns\">";
        $body .= "<cnpj>$cnpj</cnpj>";
        $body .= "<situacaoInformacao>$sit</situacaoInformacao>";
        $body .= "<anoMesInicioVigencia>$anomesIni</anoMesInicioVigencia>";
        $body .= "<anoMesTerminoVigencia>$anomesFim</anoMesTerminoVigencia>";
        if ($tpmov != '') {
            $body .= "<tipoMovimento>$tpmov</tipoMovimento>";
        }
        //se existe tpni tem que existir numni e vice-versa
        if ($tpni != '') {
            $body .= "<tipoIdentificacao>$tpni</tipoIdentificacao>";
            $body .= "<identificacao>$numni</identificacao>";
        }
        $body .= "</ConsultarInformacoesMovimento>";
        $aRet = $this->zSend($urlService, $body, $method);
        $lastMsg = $aRet['lastMsg'];
        $retorno = $aRet['retorno'];
        $this->soapDebug = $aRet['soapDebug'];
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaMovimento.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        $filename = "$mark-retConsultaMovimento.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * Consulta informações do Intermediário
     *
     * @param  string $cnpj  CNPJ da empresa declarante
     * @param  string $ginn  GIIN do intermediário
     * @param  string $tpni  Identificação do Intermediário - Tipo NI
     *         1 - CPF
     *         2 - CNPJ
     *         3 - NIF Pessoa Física (Número de Identificação Fiscal Pessoa Física)
     *         4 - NIF Pessoa Jurídica (Número de Identificação Fiscal Pessoa Jurídica)
     *         5 - Passaporte
     * @param  string $numni  Identificação do Intermediário
     * @param  array  $aResp variável passada como referencia irá conter
     *                       os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesIntermediario(
        $cnpj = '',
        $ginn = '',
        $tpni = '',
        $numni = '',
        &$aResp = array()
    ) {
        if ($cnpj == '' && $tpni == '' && $identificacao == '') {
            $msg = 'Algum dos parametros deve ser passado.';
            throw new InvalidArgumentException($msg);
        }
        $urlService = $this->url[$this->tpAmb]['consulta'];
        $method = 'ConsultarInformacoesIntermediario';
        $body = "<ConsultarInformacoesIntermediario xmlns=\"$this->xmlns\">";
        $body .= "<cnpj>$cnpj</cnpj>";
        if ($ginn != '') {
            $body .= "<GINN>$ginn</GINN>";
        }
        //se existe tpni tem que existir numni e vice-versa
        if ($tpni != '') {
            $body .= "<TipoNI>$tpni</TipoNI>";
            $body .= "<NumeroIdentificacao>$numni</NumeroIdentificacao>";
        }
        $body .= "</ConsultarInformacoesIntermediario>";
        $aRet = $this->zSend($urlService, $body, $method);
        $lastMsg = $aRet['lastMsg'];
        $retorno = $aRet['retorno'];
        $this->soapDebug = $aRet['soapDebug'];
      //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaIntermediario.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        $filename = "$mark-retConsultaIntermediario.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * Consulta informações do Patrocinado
     *
     * @param  string $cnpj  CNPJ da empresa declarante
     * @param  string $ginn  GINN do patrocinado
     * @param  string $identificacao Numero de identificação do patrocinado
     * @param  array  $aResp variável passada como referencia irá conter
     *                       os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesPatrocinado(
        $cnpj,
        $ginn = '',
        $identificacao = '',
        &$aResp = array()
    ) {
        if ($cnpj == '' || ($ginn == '' && $identificacao == '')) {
            $msg = 'Algum dos parametros deve ser passado.';
            throw new InvalidArgumentException($msg);
        }
        $urlService = $this->url[$this->tpAmb]['consulta'];
        $method = 'ConsultarInformacoesPatrocinado';
        $body = "<ConsultarInformacoesPatrocinado xmlns=\"$this->xmlns\">";
        $body .= "<cnpj>$cnpj</cnpj>";
        if ($ginn != '') {
            $body .= "<GINN>$ginn</GINN>";
        }
        if ($identificacao != '') {
            $body .= "<NumeroIdentificacao>$identificacao</NumeroIdentificacao>";
        }
        $body .= "</ConsultarInformacoesPatrocinado>";
        $aRet = $this->zSend($urlService, $body, $method);
        $lastMsg = $aRet['lastMsg'];
        $retorno = $aRet['retorno'];
        $this->soapDebug = $aRet['soapDebug'];
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaPatrocinado.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        $filename = "$mark-retConsultaPatrocinado.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
   
    /**
     * Monta lote de eventos para envio
     * se os eventos não estiverem assinados assina antes de montar o lote
     *
     * @param  array $aEv   xml dos eventos a colocar no lote
     * @param  array $aResp variável passada como referencia irá conter
     *                      os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     * @throws InvalidArgumentException
     */
    
    public function enviaLote($aEv, &$aResp = array())
    {
        if (empty($aEv)) {
            $msg = 'É obrigatório a passagem do array de eventos.';
            throw new InvalidArgumentException($msg);
        }
        if (! is_array($aEv)) {
            $msg = 'É obrigatório a passagem do ARRAY de eventos.';
            throw new InvalidArgumentException($msg);
        }
        $num = count($aEv);
        if ($num > 100) {
            $msg = 'Somente podem ser mandados até 100 eventos por lote.';
            throw new InvalidArgumentException($msg);
        }
        $urlService = $this->url[$this->tpAmb]['recepcao'];
        $method = 'ReceberLoteEvento';
        $body = "<loteEventos xmlns=\"$this->xmlns\">";
        $body .= "<eFinanceira "
                . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" "
                . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
                . "xmlns=\"http://www.eFinanceira.gov.br/schemas/envioLoteEventos/v1_0_1\">";
        $iCount = 0;
        $body .= "<loteEventos>";
        foreach ($aEv as $evento) {
            $eventol = str_replace(
                array('<?xml version="1.0" encoding="UTF-8"?>',
                    '<?xml version="1.0" encoding="utf-8"?>'),
                '',
                $evento
            );
                $body .= "<evento id=\"ID".$iCount."\">";
                $body .= $eventol;
                $body .= "</evento>";
                $iCount++;
        }
        $body .= "</loteEventos>";
        $body .= "</eFinanceira>";
        $body .= "</loteEventos>";
        //file_put_contents('lote.xml', $body);
        //exit();
        $aRet = $this->zSend($urlService, $body, $method);
        $lastMsg = $aRet['lastMsg'];
        $retorno = $aRet['retorno'];
        $this->soapDebug = $aRet['soapDebug'];
        if (! $retorno) {
            return $this->errors;
        }
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-enviaLote.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        $filename = "$mark-retEnviaLote.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], $this->aConfig['cnpj']);
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
}
