<?php

namespace NFePHP\eFinanc;

/**
 * Class Tools, performs communication with the federal revenue webservice
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use stdClass;
use NFePHP\Common\Validator;
use NFePHP\eFinanc\Common\Tools as Base;
use NFePHP\eFinanc\Common\Crypto;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Exception\EventsException;
use NFePHP\eFinanc\Exception\ProcessException;
use NFePHP\eFinanc\Exception\ConsultException;

class Tools extends Base
{
    const MODO_NORMAL = 0;
    const MODO_ZIP = 1;
    const MODO_CRYPTO = 2;
    const MODO_CRYPTOZIP = 3;

    /**
     * @var array
     */
    private $available;
    /**
     * @var stdClass
     */
    private $urls;

    /**
     * Constructor
     * @param string $config
     * @param \NFePHP\Common\Certificate $certificate
     */
    public function __construct(
        string $config,
        \NFePHP\Common\Certificate $certificate
    ) {
        parent::__construct($config, $certificate);
        $this->available = get_class_methods($this);
        $this->urls = new \stdClass();
        $this->urls->recepcao = 'https://preprod-efinanc.receita.fazenda.gov.br'
            . '/WsEFinanceira/WsRecepcao.asmx';
        $this->urls->compact = 'https://preprod-efinanc.receita.fazenda.gov.br'
            . '/WsEFinanceira/WsRecepcao.asmx';
        $this->urls->crypto = 'https://preprod-efinanc.receita.fazenda.gov.br'
            . '/WsEFinanceiraCripto/WsRecepcaoCripto.asmx';
        $this->urls->consulta = 'https://preprod-efinanc.receita.fazenda.gov.br'
            . '/WsEFinanceira/WsConsulta.asmx';
        if ($this->tpAmb == 1) {
            $this->urls->recepcao = 'https://efinanc.receita.fazenda.gov.br'
                . '/WsEFinanceira/WsRecepcao.asmx';
            $this->urls->compact = 'https://efinanc.receita.fazenda.gov.br'
                . '/WsEFinanceira/WsRecepcao.asmx';
            $this->urls->crypto = 'https://efinanc.receita.fazenda.gov.br'
                . '/WsEFinanceiraCripto/WsRecepcaoCripto.asmx';
            $this->urls->consulta = 'https://efinanc.receita.fazenda.gov.br'
                . '/WsEFinanceira/WsConsulta.asmx';
        }
    }

    /**
     * This method performs the desired query to the webservice
     * @param string $type indicate the query to be used
     * @param stdClass $std contain the parameters of this query
     * @return string xml webservice response
     * @throws EventsException
     */
    public function consultar(string $type, stdClass $std):string
    {
        $type = lcfirst($type);
        if (!in_array($type, $this->available)) {
            //esta consulta não foi localizada
            throw EventsException::wrongArgument(1000, $type);
        }
        return $this->$type($std);
    }

    /**
     * This method sends the events to the webservice
     * @param array $events
     * @param integer $modo
     * @return string xml webservice response
     * @throws \InvalidArgumentException
     */
    public function enviar(array $events, $modo = self::MODO_NORMAL):string
    {
        //constructor do lote
        $body = $this->batchBuilder($events);
        $url = $this->urls->recepcao;
        $method = 'ReceberLoteEvento';
        if ($modo == self::MODO_ZIP) {
            //apenas compacta a mensagem
            $url = $this->urls->compact;
            $method = 'ReceberLoteEventoGZip';
            $zip = base64_encode(gzencode($body));
            $body = "<sped:bufferXmlGZip>$zip</sped:bufferXmlGZip>";
        } elseif ($modo == self::MODO_CRYPTO) {
            //apenas encripta a mensagem
            $url = $this->urls->crypto;
            $method = 'ReceberLoteEventoCripto';
            $crypted = base64_encode($this->sendCripto($body));
            $body = "<sped:bufferXmlComLoteCriptografado>$crypted</sped:bufferXmlComLoteCriptografado>";
        } elseif ($modo == self::MODO_CRYPTOZIP) {
            //compacta a mensagem
            $url = $this->urls->crypto;
            $method = 'ReceberLoteEventoCriptoGZip';
            $zip = gzencode($body);
            //encripta a mensagem compactada
            $crypted = base64_encode($this->sendCripto($zip));
            $body = "<sped:bufferXmlComLoteCriptografadoGZip>$crypted</sped:bufferXmlComLoteCriptografadoGZip>";
        } else {
            $body = "<sped:loteEventos>$body</sped:loteEventos>";
        }
        return $this->sendRequest($body, $method, $url);
    }

    /**
     * Este metodo permite o envio de apenas um EVENTO já renderizado em XML e assinado,
     * pelo envio encriptado e zipado.
     * Decorre da necessidade de envio de eventos com uma enorme carga de dados
     * @param string $xml
     * @return string
     */
    public function enviarEventoXmlCryptoZip(string $xml)
    {
        $layout = $this->versions['envioLoteEventos'];
        $content = "<eFinanceira "
            . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" "
            . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
            . "xmlns=\"http://www.eFinanceira.gov.br/schemas/envioLoteEventos/v$layout\">";
        $lote = date('YmdHms');
        $content .= "<loteEventos>"
            . "<evento id=\"ID1\">"
            . $xml
            . "</evento>"
            . "</loteEventos>"
            . "</eFinanceira>";
        $schema = $this->path
            . 'schemes/v'
            . $this->eventoVersion
            . '/envioLoteEventos-v'
            . $layout
            . '.xsd';
        if ($schema) {
            Validator::isValid($content, $schema);
        }
        $url = $this->urls->crypto;
        $method = 'ReceberLoteEventoCriptoGZip';
        $zip = gzencode($content);
        //encripta a mensagem compactada
        $crypted = base64_encode($this->sendCripto($zip));
        $body = "<sped:bufferXmlComLoteCriptografadoGZip>$crypted</sped:bufferXmlComLoteCriptografadoGZip>";
        return $this->sendRequest($body, $method, $url);
    }

    /**
     * Allow the registration of new certificates for encrypted messages
     * @param string $derdata certificate content in DER format (usual)
     */
    public function setCertificateEFinanceira($derdata)
    {
        $crypto = new Crypto($derdata);
        $this->der = $derdata;
    }

    /**
     * This method constructs the event batch
     * @param array $events
     * @return string
     * @throws ProcessException
     */
    private function batchBuilder(array $events)
    {
        if (empty($events)) {
            //não foram passados os eventos
            throw ProcessException::wrongArgument(2002, '');
        }
        if (! is_array($events)) {
            //não foram passados os eventos
            throw ProcessException::wrongArgument(2002, '');
        }
        $num = count($events);
        if ($num > 100) {
            //excedido o numero máximo de eventos
            throw ProcessException::wrongArgument(2000, $num);
        }
        $layout = $this->versions['envioLoteEventos'];
        $xml = "<eFinanceira "
                . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" "
                . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
                . "xmlns=\"http://www.eFinanceira.gov.br/schemas/envioLoteEventos/v$layout\">";
        $iCount = 0;
        $lote = date('YmdHms');
        $xml .= "<loteEventos>";
        foreach ($events as $evt) {
            if (!is_a($evt, '\NFePHP\eFinanc\Common\FactoryInterface')) {
                throw ProcessException::wrongArgument(2002, '');
            }
            $this->checkCertificate($evt);
            $xml .= "<evento id=\"ID".$iCount."\">";
            $xml .= $evt->toXML();
            $xml .= "</evento>";
            $iCount++;
        }
        $xml .= "</loteEventos>";
        $xml .= "</eFinanceira>";
        $schema = $this->path
            . 'schemes/v'
            . $this->eventoVersion
            . '/envioLoteEventos-v'
            . $layout
            . '.xsd';
        if ($schema) {
            Validator::isValid($xml, $schema);
        }
        return $xml;
    }

    /**
     * This method encrypts the event batch
     * @param string $body
     * @return string
     * @throws ProcessException
     */
    public function sendCripto($body)
    {
        if (empty($this->der)) {
            //deve existir um certificado do servidor da Receita
            throw ProcessException::wrongArgument(2003, '');
        }
        $crypt = new Crypto($this->der);
        $resp = $crypt->certificateInfo();
        $dt = new \DateTime();
        if ($resp['validTo'] < $dt) {
            //a validade do certificado expirou
            throw ProcessException::wrongArgument(2004, '');
        }
        $id = 1;
        $layout = $this->versions['envioLoteCriptografado'];
        $key = $crypt->getEncrypedKey();
        $fingerprint = $crypt->getThumbprint();
        $crypted = $crypt->encryptMsg($body);
        $msg = "<eFinanceira xmlns=\"http://www.eFinanceira.gov.br/schemas"
            . "/envioLoteCriptografado/v$layout\">"
            . "<loteCriptografado>"
            . "<id>$id</id>"
            . "<idCertificado>$fingerprint</idCertificado>"
            . "<chave>$key</chave>"
            . "<lote>$crypted</lote>"
            . "</loteCriptografado>"
            . "</eFinanceira>";
        $schema = $this->path
            . 'schemes/v'
            . $this->eventoVersion
            . '/envioLoteCriptografado-v'
            . $layout
            . '.xsd';
        if ($schema) {
            Validator::isValid($msg, $schema);
        }
        return $msg;
    }

    /**
     * This method consults "Informacoes Cadastrais"
     * @param stdClass $std
     * @return string
     * @throws ConsultException
     */
    protected function consultarInformacoesCadastrais(stdClass $std):string
    {
        if (empty($std->cnpj) && !preg_match("/^[0-9]{14}/", $std->cnpj)) {
            throw ConsultException::wrongArgument(
                'O CNPJ da empresa declarante deve ser informado para essa consulta'
            );
        }
        $method = 'ConsultarInformacoesCadastrais';
        $body = "<sped:$method><sped:cnpj>$std->cnpj</sped:cnpj></sped:$method>";
        return $this->sendRequest($body, $method, $this->urls->consulta);
    }

    /**
     * This method consults "Informacoes Intermediario"
     * @param stdClass $std
     * @return string
     * @throws ConsultException
     */
    protected function consultarInformacoesIntermediario(stdClass $std):string
    {
        $possible = [
            'cnpj',
            'giin',
            'tiponi',
            'numeroidentificacao'
        ];
        $std = $this->equilizeParameters($std, $possible);
        if (empty($std->cnpj) && !preg_match("/^[0-9]{14}/", $std->cnpj)) {
            throw ConsultException::wrongArgument(
                'O CNPJ da empresa declarante deve ser informado para essa consulta.'
            );
        }
        if (empty($std->ginn) && empty($std->numeroidentificacao)) {
            throw ConsultException::wrongArgument(
                'Algum dado do intermediário deve ser passado.'
            );
        }
        if (!empty($std->giin)) {
            if (preg_match("/^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|"
                    . "SF|SD|SS|SB|SP)[.][0-9]{3})$/", $std->giin)) {
                throw ConsultException::wrongArgument(
                    'Este GIIN passado não atende a estrutura estabelecida.'
                );
            }
        }
        $method = 'ConsultarInformacoesIntermediario';
        $body = "<sped:$method><sped:cnpj>$std->cnpj</sped:cnpj>";
        if (!empty($ginn)) {
            $body .= "<sped:GINN>$std->giin</sped:GINN>";
        } elseif (!empty($std->numeroidentificacao)) {
            $body .= "<sped:TipoNI>$std->tiponi</sped:TipoNI>"
            . "<sped:NumeroIdentificacao>$std->numeroidentificacao</sped:NumeroIdentificacao>";
        } else {
            throw ConsultException::wrongArgument(
                'Deve ser indicado algum documento do Intermediario.'
            );
        }
        $body .= "</sped:$method>";
        return $this->sendRequest($body, $method, $this->urls->consulta);
    }

    /**
     * This method consults "Informacoes Movimento"
     * @param stdClass $std
     * @return string
     * @throws ConsultException
     */
    protected function consultarInformacoesMovimento(stdClass $std):string
    {
        $possible = [
            'cnpj',
            'situacaoinformacao',
            'anomesiniciovigencia',
            'anomesterminovigencia',
            'tipomovimento',
            'tipoidentificacao',
            'identificacao'
        ];
        $std = $this->equilizeParameters($std, $possible);
        if (empty($std->cnpj) && !preg_match("/^[0-9]{14}/", $std->cnpj)) {
            throw ConsultException::wrongArgument(
                'O CNPJ da empresa declarante deve ser informado para essa consulta.'
            );
        }
        if (!is_numeric($std->situacaoinformacao)
                || !($std->situacaoinformacao >=0 && $std->situacaoinformacao<=3)
        ) {
            throw ConsultException::wrongArgument(
                'A situação deve ser informada: 0-Todas, 1-Ativo, 2-Retificado,3-Excluído.'
            );
        }
        /*
        if (!preg_match(
            "/^(19[0-9][0-9]|2[0-9][0-9][0-9])[\/](0?[1-9]|1[0-2])$/",
            $std->anomesiniciovigencia
        )
        ) {
            throw ConsultException::wrongArgument(
                'O ano e mês do inicio da vigência deve ser informado: AAAA/MM.'
            );
        }
        if (!preg_match(
            "/^(19[0-9][0-9]|2[0-9][0-9][0-9])[\/](0?[1-9]|1[0-2])$/",
            $std->anomesterminovigencia
        )
        ) {
            throw ConsultException::wrongArgument(
                'O ano e mês do inicio do término da vigência deve ser informado: AAAA/MM.'
            );
        }
         */
        $method = 'ConsultarInformacoesMovimento';
        $body = "<sped:$method><sped:cnpj>$std->cnpj</sped:cnpj>"
           . "<sped:situacaoInformacao>$std->situacaoinformacao</sped:situacaoInformacao>"
           . "<sped:anoMesInicioVigencia>$std->anomesiniciovigencia</sped:anoMesInicioVigencia>"
           . "<sped:anoMesTerminoVigencia>$std->anomesterminovigencia</sped:anoMesTerminoVigencia>";
        if (!empty($std->tipomovimento)) {
            if (preg_match("/[1-2]{1}/", $std->tipomovimento)) {
                $body .= "<sped:tipoMovimento>$std->tipomovimento</sped:tipoMovimento>";
            }
        }
        if (!empty($std->tipoidentificacao)) {
            if (!preg_match("/^[1-7]{1}|99$/", $std->tipoidentificacao)) {
                throw ConsultException::wrongArgument(
                    "O tipo de identificação deve ser numerico e deve estar entre 1 e 7 ou 99."
                    . " [$std->tipoidentificacao] não atende os requisitos."
                );
            }
            $body .= "<sped:tipoIdentificacao>$std->tipoidentificacao</sped:tipoIdentificacao>";
            $body .= "<sped:identificacao>$std->identificacao</sped:identificacao>";
        }
        $body .= "</sped:$method>";
        return $this->sendRequest($body, $method, $this->urls->consulta);
    }

    /**
     * This method consults "Informacoes Patrocinado"
     * @param stdClass $std
     * @return string
     * @throws ConsultException
     */
    protected function consultarInformacoesPatrocinado(stdClass $std):string
    {
        $possible = [
            'cnpj',
            'giin',
            'numeroidentificacao'
        ];
        $std = $this->equilizeParameters($std, $possible);
        if (empty($std->cnpj) && !preg_match("/^[0-9]{14}/", $std->cnpj)) {
            throw ConsultException::wrongArgument(
                'O CNPJ da empresa declarante deve ser informado para essa consulta.'
            );
        }
        if (empty($std->ginn) && empty($std->numeroidentificacao)) {
            throw ConsultException::wrongArgument(
                'Algum dado do patrocinado deve ser passado.'
            );
        }
        if (!empty($std->giin)) {
            if (!preg_match("/^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF"
                    . "|SD|SS|SB|SP)[.][0-9]{3})$/", $std->giin)) {
                throw ConsultException::wrongArgument(
                    'Este GIIN passado não atende a estrutura estabelecida.'
                );
            }
        }
        $method = 'ConsultarInformacoesPatrocinado';
        $body = "<sped:$method><sped:cnpj>$std->cnpj</sped:cnpj>";
        if (!empty($std->giin)) {
            $body .= "<sped:GINN>$std->giin</sped:GINN>";
        }
        if (!empty($std->numeroidentificacao)) {
            $body .= "<sped:NumeroIdentificacao>$std->numeroidentificacao</sped:NumeroIdentificacao>";
        }
        $body .= "</sped:$method>";
        return $this->sendRequest($body, $method, $this->urls->consulta);
    }

    /**
     * This method consults "Informacoes Rerct"
     * @param stdClass $std
     * @return string
     * @throws ConsultException
     */
    protected function consultarInformacoesRerct(stdClass $std):string
    {
        $possible = [
            'ideventorerct',
            'situacaoinformacao',
            'numerorecibo',
            'cnpjdeclarante',
            'tipoinscricaodeclarado',
            'inscricaodeclarado',
            'tipoinscricaotitular',
            'inscricaotitular',
            'cpfbeneficiariofinal'
        ];
        $std = $this->equilizeParameters($std, $possible);
        if (empty($std->cnpjdeclarante) && !preg_match("/^[0-9]{14}/", $std->cnpjdeclarante)) {
            throw ConsultException::wrongArgument(
                'O CNPJ da empresa declarante deve ser informado para essa consulta.'
            );
        }
        if (!is_numeric($std->ideventorerct)
                || !($std->ideventorerct == 1 || $std->ideventorerct == 2)
        ) {
            throw ConsultException::wrongArgument(
                'A Identificação do Evento RERCT deve ser informada.'
            );
        }
        if (!is_numeric($std->situacaoinformacao)
                || !($std->situacaoinformacao >=0 && $std->situacaoinformacao<=3)
        ) {
            throw ConsultException::wrongArgument(
                'A situação deve ser informada: 0-Todas, 1-Ativo, 2-Retificado,3-Excluído.'
            );
        }
        $method = 'ConsultarInformacoesRerct';
        $body = "<sped:$method><sped:idEventoRerct>$std->ideventorerct</sped:idEventoRerct>"
            . "<sped:situacaoInformacao>$std->situacaoinformacao</sped:situacaoInformacao>";

        if (preg_match("/^([0-9]{1,18}[-][0-9]{2}[-][0-9]{3}[-][0-9]{4}[-][0-9]{1,18})$/", $std->numerorecibo)) {
            $body .= "<sped:numeroRecibo>$std->numerorecibo</sped:numeroRecibo>";
        }
        $body .= "<sped:cnpjDeclarante>$std->cnpjdeclarante</sped:cnpjDeclarante>";
        if (preg_match('/[0-9]{11,14}/', $std->inscricaodeclarado)) {
            $body .= "<sped:tipoInscricaoDeclarado>$std->tipoinscricaodeclarado</sped:tipoInscricaoDeclarado>"
                . "<sped:inscricaoDeclarado>$std->inscricaodeclarado</sped:inscricaoDeclarado>";
        }
        if (preg_match('/[0-9]{11,14}/', $std->inscricaotitular)) {
            $body .= "<sped:tipoInscricaoTitular>$std->tipoinscricaotitular</sped:tipoInscricaoTitular>"
                . "<sped:inscricaoTitular>$std->inscricaotitular</sped:inscricaoTitular>";
        }
        if (preg_match('/[0-9]{11}/', $std->cpfbeneficiariofinal)) {
            $body .= "<sped:cpfBeneficiarioFinal>$std->cpfbeneficiariofinal</sped:cpfBeneficiarioFinal>";
        }
        $body .= "</sped:$method>";
        return $this->sendRequest($body, $method, $this->urls->consulta);
    }

    /**
     * This method consults "Lista EFinanceira"
     * @param stdClass $std
     * @return string
     * @throws ConsultException
     */
    protected function consultarListaEFinanceira(stdClass $std):string
    {
        $possible = [
            'cnpj',
            'situacaoefinanceira',
            'datainicio',
            'dataFim'
        ];
        $std = $this->equilizeParameters($std, $possible);
        if (empty($std->cnpj) && !preg_match("/^[0-9]{14}/", $std->cnpj)) {
            throw ConsultException::wrongArgument(
                'O CNPJ da empresa declarante deve ser informado para essa consulta.'
            );
        }
        if (!is_numeric($std->situacaoefinanceira)
                || !($std->situacaoefinanceira >=0 && $std->situacaoefinanceira<=4)
        ) {
            throw ConsultException::wrongArgument(
                'A situação deve ser informada: 0-Todas,1-Em Andamento,2-Ativa,'
                    . '3-Retificada,4-Excluída.'
            );
        }
        $method = 'ConsultarListaEFinanceira';
        $body = "<sped:$method><sped:cnpj>$std->cnpj</sped:cnpj>"
            . "<sped:situacaoEFinanceira>$std->situacaoefinanceira</sped:situacaoEFinanceira>";
        if (!empty($std->datainicio)) {
            $body .= "<sped:dataInicio>$std->datainicio</sped:dataInicio>";
        }
        if (!empty($std->datafim)) {
            $body .= "<sped:dataFim>$std->datafim</sped:dataFim>";
        }
        $body .= "</sped:$method>";
        return $this->sendRequest($body, $method, $this->urls->consulta);
    }

    /**
     * Verify the availability of a digital certificate.
     * If available, place it where it is needed
     * @param FactoryInterface $evento
     * @return void
     */
    protected function checkCertificate(FactoryInterface $evento)
    {
        //try to get certificate from event
        $certificate = $evento->getCertificate();
        if (empty($certificate)) {
            $evento->setCertificate($this->certificate);
        }
    }
}
