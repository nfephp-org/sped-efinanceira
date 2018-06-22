<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_0', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 2;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->dtinicio = '2017-01-01';
$std->dtfim = '2017-05-31';
$std->sitespecial = 1;

$std->fechamentopp = new \stdClass();
$std->fechamentopp->fechamentomes[0] = new \stdClass();
$std->fechamentopp->fechamentomes[0]->anomescaixa = '201713';
$std->fechamentopp->fechamentomes[0]->quantarqtrans = 99999;

$std->fechamentomovopfin = new \stdClass();
$std->fechamentomovopfin->fechamentomes[0] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[0]->anomescaixa = '201713';
$std->fechamentomovopfin->fechamentomes[0]->quantarqtrans = 99999;

$std->fechamentomovopfin->entdecexterior = new \stdClass();
$std->fechamentomovopfin->entdecexterior->contasareportar = 0;

$std->fechamentomovopfin->entpatdecexterior[0] = new \stdClass();
$std->fechamentomovopfin->entpatdecexterior[0]->giin = '12ASDA.12345.LE.123';
$std->fechamentomovopfin->entpatdecexterior[0]->cnpj = '12345678901234';
$std->fechamentomovopfin->entpatdecexterior[0]->contasareportar = 0;


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
        '2017-08-03 10:37:00',
        '1_2_1'
    )->toXml();
    
    //$xml = Event::f4000($json, $std, $certificate)->toXML();
    //$json = Event::evtFechamentoeFinanceira($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
