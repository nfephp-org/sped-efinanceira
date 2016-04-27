<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';


use NFePHP\eFinanc\Tools;

$conf = json_decode(file_get_contents('../config/config.json'));

$tools = new Tools('../config/config.json');

$aResp = array();
$cnpj = $conf->cnpj;
$sit = '0';
$anomesIni = '2015/07';
$anomesFim = '2015/12';
$tpmov = '1';
$tpni = '2';
$numni = '21810869000171';
$retorno = $tools->consultarInformacoesMovimento($cnpj, $sit, $anomesIni, $anomesFim, $tpmov, $tpni, $numni, $aResp);

var_dump($retorno);
var_dump($aResp);