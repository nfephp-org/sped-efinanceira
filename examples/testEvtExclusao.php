<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\Exclusao;
use NFePHP\eFinanc\Tools;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new Exclusao('../config/config.json');

//########################################################################
$id = '000000000000000001';
$indRetificacao = ''; // 1 - para arquivo original 2 - para arquivo de retificação espontânea 3 – para arquivo de retificação a pedido
$tpAmb = 2;
$evt->tagEvento($id, $indRetificacao, $tpAmb);

//########################################################################
$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);

//########################################################################
$nrRecibo = '1234-56-789-0123-4501';
$evt->tagInfo($nrRecibo);

//########################################################################
$evt->monta();

//########################################################################
$evt->assina();

//########################################################################
if (! $evt->valida()) {
    var_dump($evt->errors);
    exit();
}
//########################################################################
//header('Content-type: text/xml; charset=UTF-8');
$xml = $evt->getXML();


$tools = new Tools('../config/config.json');

$aEv = array($xml);



$aResp = array();
$retorno = $tools->enviaLote($aEv, $aResp);

var_dump($retorno);
var_dump($aResp);

