<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 1, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '1', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_4', //versão do layout do evento
    'cnpjDeclarante' => '14388516000160'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '11630';
$std->indretificacao = 1;
//$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->dtinicio = '2017-07-01';
$std->dtfim = '2017-12-31';
$std->sitespecial = 0;

/*
$std->fechamentopp = new \stdClass();
$std->fechamentopp->fechamentomes[0] = new \stdClass();
$std->fechamentopp->fechamentomes[0]->anomescaixa = '201713';
$std->fechamentopp->fechamentomes[0]->quantarqtrans = 99999;
*/

$std->fechamentomovopfin = new \stdClass();
$std->fechamentomovopfin->fechamentomes[0] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[0]->anomescaixa = '201707';
$std->fechamentomovopfin->fechamentomes[0]->quantarqtrans = 40;

$std->fechamentomovopfin->fechamentomes[1] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[1]->anomescaixa = '201708';
$std->fechamentomovopfin->fechamentomes[1]->quantarqtrans = 50;

$std->fechamentomovopfin->fechamentomes[2] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[2]->anomescaixa = '201709';
$std->fechamentomovopfin->fechamentomes[2]->quantarqtrans = 33;

$std->fechamentomovopfin->fechamentomes[3] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[3]->anomescaixa = '201710';
$std->fechamentomovopfin->fechamentomes[3]->quantarqtrans = 22;

$std->fechamentomovopfin->fechamentomes[4] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[4]->anomescaixa = '201711';
$std->fechamentomovopfin->fechamentomes[4]->quantarqtrans = 68;

$std->fechamentomovopfin->fechamentomes[5] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[5]->anomescaixa = '201712';
$std->fechamentomovopfin->fechamentomes[5]->quantarqtrans = 58;

/*
$std->fechamentomovopfin->entdecexterior = new \stdClass();
$std->fechamentomovopfin->entdecexterior->contasareportar = 0;
*/
/*
$std->fechamentomovopfin->entpatdecexterior[0] = new \stdClass();
$std->fechamentomovopfin->entpatdecexterior[0]->giin = '12ASDA.12345.LE.123';
$std->fechamentomovopfin->entpatdecexterior[0]->cnpj = '12345678901234';
$std->fechamentomovopfin->entpatdecexterior[0]->contasareportar = 0;
*/
/*
$std->fechamentomovopfinanual = new \stdClass();
$std->fechamentomovopfinanual->fechamentoano = new \stdClass();
$std->fechamentomovopfinanual->fechamentoano->anocaixa = '2017';
$std->fechamentomovopfinanual->fechamentoano->quantarqtrans = 271;
*/

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
