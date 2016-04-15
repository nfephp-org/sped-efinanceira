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

use NFePHP\Common\Certificate\Pkcs12;
use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Dom\Dom;
use NFePHP\Common\Soap\CurlSoap;
use NFePHP\Common\Files;
use RuntimeException;
use InvalidArgumentException;

class Tools
{
    /**
     * verAplic
     * Versão da aplicação
     * @var string
     */
    public $verAplic = '';
    /**
     * certExpireTimestamp
     * TimeStamp com a data de vencimento do certificado
     * @var double
     */
    public $certExpireTimestamp = 0;
    /**
     * String com a data de expiração da validade
     * do certificado digital no9 formato dd/mm/aaaa
     *
     * @var string
     */
    public $certExpireDate = '';
    /**
     * tpAmb
     * @var int
     */
    public $tpAmb = 2;
    /**
     * ambiente
     * @var string
     */
    public $ambiente = 'homologacao';
    /**
     * aConfig
     * @var array
     */
    public $aConfig = array();
    /**
     * sslProtocol
     * @var int
     */
    public $sslProtocol = 0;
    /**
     * soapTimeout
     * @var int
     */
    public $soapTimeout = 10;
    /**
     * oCertificate
     * @var Object Class
     */
    public $oCertificate;
    /**
     * oSoap
     * @var Object Class
     */
    public $oSoap;
    /**
     * soapDebug
     * @var string
     */
    public $soapDebug = '';
    /**
     * XMLNS namespace
     * @var string
     */
    public $xmlns = "http://sped.fazenda.gov.br/";
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
     * __construct
     * @param string $configJson
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct($configJson = '')
    {
        if ($configJson == '') {
            $msg = 'O arquivo de configuração no formato JSON deve ser passado para a classe.';
            throw new InvalidArgumentException($msg);
        }
        if (is_file($configJson)) {
            $configJson = Files\FilesFolders::readFile($configJson);
        }
        //carrega os dados de configuração
        $this->aConfig      = (array) json_decode($configJson);
        $this->aProxyConf   = (array) $this->aConfig['aProxyConf'];
        $this->verAplic     = $this->aConfig['verAplic'];
        //seta o timezone
        DateTime::tzdBR($this->aConfig['siglaUF']);
        //carrega os certificados
        $this->oCertificate = new Pkcs12(
            $this->aConfig['pathCertsFiles'],
            $this->aConfig['cnpj']
        );
        if ($this->oCertificate->priKey == '') {
            //tentar carregar novo certificado
            $this->atualizaCertificado(
                $this->aConfig['pathCertsFiles'].$this->aConfig['certPfxName'],
                $this->aConfig['certPassword']
            );
            if ($this->oCertificate->expireTimestamp == 0) {
                $msg = 'Não existe certificado válido disponível. Atualize o Certificado.';
                throw new RuntimeException($msg);
            }
        }
        $this->setAmbiente($this->aConfig['tpAmb']);
        $this->certExpireTimestamp = $this->oCertificate->expireTimestamp;
        $this->certExpireDate = date('d/m/Y', $this->certExpireTimestamp);
        $this->loadSoapClass();
    }
    
   /**
    * getCertTimestamp
    * Retorna o timestamp para a data de vencimento do Certificado
    *
    * @return int
    */
    public function getCertTimestamp()
    {
        return $this->certExpireTimestamp;
    }

   /**
    * getCertValidity
    * Retorna o timestamp para a data de vencimento do Certificado
    *
    * @return int
    */
    public function getCertValidity()
    {
        return $this->certExpireDate;
    }
    
    /**
     * setSSLProtocol
     * Força o uso de um determinado protocolo de encriptação
     * na comunicação https com a SEFAZ usando cURL
     * Apenas é necessário quando a versão do PHP e do libssl não
     * consegue estabelecer o protocolo correto durante o handshake
     * @param string $protocol
     */
    public function setSSLProtocol($protocol = '')
    {
        if (! empty($protocol)) {
            switch ($protocol) {
                case 'TLSv1':
                    $this->sslProtocol = 1;
                    break;
                case 'SSLv2':
                    $this->sslProtocol = 2;
                    break;
                case 'SSLv3':
                    $this->sslProtocol = 3;
                    break;
                case 'TLSv1.0':
                    $this->sslProtocol = 4;
                    break;
                case 'TLSv1.1':
                    $this->sslProtocol = 5;
                    break;
                case 'TLSv1.2':
                    $this->sslProtocol = 6;
                    break;
                default:
                    $this->sslProtocol = 0;
            }
            $this->loadSoapClass();
        }
    }
    
    /**
     * getSSLProtocol
     * Retorna o protocolo que está setado
     * Se for indicada qualquer opção no parametro será retornada as possiveis
     * opções para o protocolo SSL
     *
     * @return string | array protocolo setado ou array de opções
     */
    public function getSSLProtocol($opt = '')
    {
        $aPr = array('automatic','TLSv1','SSLv2','SSLv3','TLSv1.0','TLSv1.1','TLSv1.2');
        if ($opt == '') {
            return $aPr[$this->sslProtocol];
        }
        return $aPr;
    }
    
    /**
     * setSoapTimeOut
     * Seta um valor para timeout
     *
     * @param integer $segundos
     */
    public function setSoapTimeOut($segundos = 10)
    {
        if (! empty($segundos)) {
            $this->soapTimeout = $segundos;
            $this->loadSoapClass();
        }
    }
    
    /**
     * getSoapTimeOut
     * Retorna o valor de timeout defido
     *
     * @return integer
     */
    public function getSoapTimeOut()
    {
        return $this->soapTimeout;
    }
    
    /**
     * setAmbiente
     * Seta a varável de ambiente
     *
     * @param string $tpAmb
     */
    protected function setAmbiente($tpAmb = '2')
    {
        $this->ambiente = 'homologacao';
        if ($tpAmb == '1') {
            $this->ambiente = 'producao';
        }
    }
    
    /**
     * atualizaCertificado
     * Permite a atualização de um novo certificado já colocado na pasta dos
     * certificados definida no config.json
     *
     * @param string $certpfx certificado pfx em string ou o nome do arquivo do certificado
     * @param string $senha senha para abrir o certificado
     * @return boolean
     */
    public function atualizaCertificado($certpfx = '', $senha = '')
    {
        if ($certpfx == '' && $senha != '') {
            return false;
        }
        if (is_file($certpfx)) {
            $this->oCertificate->loadPfxFile($certpfx, $senha);
            return true;
        }
        $this->oCertificate->loadPfx($certpfx, $senha);
        $this->loadSoapClass();
        return true;
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
        $retorno = $this->oSoap->send($urlService, '', '', $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaCad.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        $filename = "$mark-retConsultaCad.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * Consulta Lista EFinanceira
     *
     * @param  string $cnpj CNPJ do Declarante
     * @param  string $sit Situacao EFinanceira 0 – Todas
                                                    1 – Em Andamento
                                                    2 – Ativa
                                                    3 - Retificada
                                                    4 - Excluída
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
        if ($cnpj == '' || $sitInfo == '') {
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
        $retorno = $this->oSoap->send($urlService, '', '', $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaLista.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        $filename = "$mark-retConsultaLista.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     * Consulta Informações de Movimento
     *
     * @param  string $cnpj CNPJ da empresa declarante
     * @param  string $sit  Situacao    0 - Todas
                                        1 - Em Andamento
                                        2 - Ativa
                                        3 - Retificada
                                        4 - Excluída
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
        if ($cnpj == '' || $sit == '' || $anomesIni = '' || $anomesFim == '') {
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
        $retorno = $this->oSoap->send($urlService, '', '', $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaMovimento.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        $filename = "$mark-retConsultaMovimento.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
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
     * @param  string $numni Identificação do Intermediário - Número do NI
     * @param  array  $aResp variável passada como referencia irá conter
     *                       os retornos em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesIntermediario(
        $cnpj = '',
        $ginn = '',
        $tpni = '',
        $identificacao = '',
        &$aResp = array()
    ) {
        if ($cnpj == '' && $ginn == '' && $tpni == '' && $identificacao == '') {
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
            $body .= "<NumeroIdentificacao>$identificacao</NumeroIdentificacao>";
        }
        $body .= "</ConsultarInformacoesIntermediario>";
        $retorno = $this->oSoap->send($urlService, '', '', $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaIntermediario.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        $filename = "$mark-retConsultaIntermediario.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
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
        $retorno = $this->oSoap->send($urlService, '', '', $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-consultaPatrocinado.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        $filename = "$mark-retConsultaPatrocinado.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
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
        $method = 'EnviarLoteEvento';
        $body = "<eFinanceira "
                . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" "
                . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
                . "xmlns=\"http://www.eFinanceira.gov.br/schemas/envioLoteEventos/v1_0_1\">";
        $body .= "<loteEventos>";
        $iCount = 0;
        foreach ($aEv as $evento) {
            $body .= "<evento id=\"ID$iCount\">";
            $evento = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $evento);
            $body .= $evento;
            $body .= "</evento>";
            $iCount++;
        }
        $body .= "</loteEventos>";
        $body .= "</eFinanceira>";
        $retorno = $this->oSoap->send($urlService, '', '', $body, $method);
        $lastMsg = $this->oSoap->lastMsg;
        $this->soapDebug = $this->oSoap->soapDebug;
        //salva comunicações para log
        $mark = $this->generateMark();
        $filename = "$mark-enviaLote.xml";
        $this->gravaFile($lastMsg, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        $filename = "$mark-retEnviaLote.xml";
        $this->gravaFile($retorno, $filename, $this->tpAmb, $this->aConfig['pathFiles'], 'temporarias');
        //tratar dados de retorno
        $aResp = Response::readReturn($method, $retorno);
        return (string) $retorno;
    }
    
    /**
     *
     * @param string $data conteudo a ser gravado
     * @param string $filename
     * @param int $tpAmb
     * @param string $folder
     * @param string $subFolder
     * @throws RuntimeException
     */
    protected function gravaFile(
        $data,
        $filename,
        $tpAmb = '2',
        $folder = '',
        $subFolder = 'temporarias'
    ) {
        $anomes = date('Ym');
        $pathTemp = Files\FilesFolders::getFilePath($tpAmb, $folder, $subFolder)
            . DIRECTORY_SEPARATOR . $anomes;
        if (! Files\FilesFolders::saveFile($pathTemp, $filename, $data)) {
            $msg = 'Falha na gravação no diretório. '.$pathTemp;
            throw new RuntimeException($msg);
        }
    }
    
    /**
     * Carrega a classe SOAP e os certificados
     */
    protected function loadSoapClass()
    {
        $this->oSoap = null;
        $this->oSoap = new CurlSoap(
            $this->oCertificate->priKeyFile,
            $this->oCertificate->pubKeyFile,
            $this->oCertificate->certKeyFile,
            $this->soapTimeout,
            $this->sslProtocol
        );
    }
    
    /**
     * Retorna uma seguencia não sujeita a repetição indicando
     * o ano, mes, dia, hora, minuto e segundo
     * Este retorno é usado para nomear os arquivos de log das
     * comunicações SOAP enviadas e retornadas
     *
     * @return string sequencia AAAMMDDHHMMSS
     */
    protected function generateMark()
    {
        return date('YmdHis');
    }
}
