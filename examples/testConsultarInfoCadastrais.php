<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';


use NFePHP\eFinanc\Tools;

$conf = json_decode(file_get_contents('../config/config.json'));

$tools = new Tools('../config/config.json');

$aResp = array();
$cnpj = $conf->cnpj;
$retorno = $tools->consultarInformacoesCadastrais($cnpj, $aResp);

var_dump($retorno);
var_dump($aResp);
