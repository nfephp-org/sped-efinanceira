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
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->ideeventorerct = 1;

$std->idedeclarado = new \stdClass();
$std->idedeclarado->tpinscr = 1;
$std->idedeclarado->nrinscr = '12345678901234';

$std->rerct[0] = new \stdClass();
$std->rerct[0]->nomebancoorigem = 'alkslskaoiaoiaoia';
$std->rerct[0]->paisorigem = 'BR';
$std->rerct[0]->bicbancoorigem = 'ODLKRTDNSRQ';

$std->rerct[0]->infocontaexterior[0] = new \stdClass();
$std->rerct[0]->infocontaexterior[0]->tpcontaexterior = 1;
$std->rerct[0]->infocontaexterior[0]->numcontaexterior = 'slsl54544';
$std->rerct[0]->infocontaexterior[0]->vlrultdia = 2500.23;
$std->rerct[0]->infocontaexterior[0]->moeda = 'ABC';

$std->rerct[0]->infocontaexterior[0]->titular[0] = new \stdClass();
$std->rerct[0]->infocontaexterior[0]->titular[0]->nometitular = 'ksjksjksjksjksj';
$std->rerct[0]->infocontaexterior[0]->titular[0]->tpinscr = 2;
$std->rerct[0]->infocontaexterior[0]->titular[0]->nrinscr = '12345678901';
$std->rerct[0]->infocontaexterior[0]->titular[0]->niftitular = '2929292929292';

$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0] = new \stdClass();
$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0]->nomebeneficiariofinal = 'lkjsljslksjksj';
$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0]->cpfbeneficiariofinal = '12345678901';
$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0]->nifbeneficiariofinal = '54545454545454';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtRERCT(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Event::f8000($json, $std, $certificate)->toXML();
    //$json = Event::evtRERCT($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
