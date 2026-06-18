<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 1, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_3_0', //versão do layout do evento
    'cnpjDeclarante' => '14388516000160'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '11630';
$std->indretificacao = 1;
//$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678'; //opcional
$std->dtinicio = '2017-07-01';
$std->dtfim = '2017-12-31';
$std->sitespecial = 0;
$std->nadaADeclarar = 1; //opcional

//opcional
$std->fechamentopp = new \stdClass();
$std->fechamentopp->movimento = 0; //0 - Ssem movimento de Previdência Privada no período ou 1 - Com movimento de Previdência Privada no período

//opcional
$std->fechamentomovopfin = new \stdClass();
$std->fechamentomovopfin->movimento = 0; //0 - Sem movimento de Operações Financeiras no período e 1 - Com movimento de Operações Financeiras no período

$std->fechamentomovopfin->entdecexterior = new \stdClass();
$std->fechamentomovopfin->entdecexterior->contasareportar = 0;

$std->fechamentomovopfin->entpatdecexterior[0] = new \stdClass();
$std->fechamentomovopfin->entpatdecexterior[0]->giin = '12ASDA.12345.LE.123';
$std->fechamentomovopfin->entpatdecexterior[0]->cnpj = '12345678901234';
$std->fechamentomovopfin->entpatdecexterior[0]->contasareportar = 0;
$std->fechamentomovopfin->entpatdecexterior[0]->incadpatrocinadoencerrado = 1; //permitidos 1 ou 0 ou null
$std->fechamentomovopfin->entpatdecexterior[0]->ingiinencerrado = 1; //permitidos 1 ou 0 ou null

$std->fechamentomovopfinanual = new \stdClass();
$std->fechamentomovopfinanual->movimento = 1; //0 - Sem movimento de Operações Financeiras Anual no período e 1 - Com movimento de Operações Financeiras Anual no período


try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtFechamentoeFinanceira(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();

    //$xml = Event::f4000($json, $std, $certificate)->toXML();
    //$json = Event::evtFechamentoeFinanceira($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
