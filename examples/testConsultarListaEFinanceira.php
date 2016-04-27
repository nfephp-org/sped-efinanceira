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
$dtInicio = '2015-07-01';
$dtFim = '2015-12-31';
$retorno = $tools->consultarListaEFinanceira($cnpj, $sit, $dtInicio, $dtFim, $aResp);

var_dump($retorno);
var_dump($aResp);
