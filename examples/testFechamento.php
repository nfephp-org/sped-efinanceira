<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\Fechamento;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new Fechamento('../config/config.json');

//########################################################################
$id = '000000000000000001';
$indRetificacao = 1; // 1 - para arquivo original 2 - para arquivo de retificação espontânea 3 – para arquivo de retificação a pedido
$tpAmb = 2;
$evt->tagEvento($id, $indRetificacao, $tpAmb);

//########################################################################
$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);

//########################################################################
$dtInicio = '2016-01-01';
$dtFim = '2016-06-31';
$sitEspecial = '0';
$evt->tagInfo($dtInicio, $dtFim, $sitEspecial);

//########################################################################
$pais = 'US';
$reportavel = '0';
$evt->reportavelExterior($pais, $reportavel);

//########################################################################
$aFech = [
    ['201601', '1'],
    ['201602', '1'],
    ['201603', '1'],
    ['201604', '1'],
    ['201605', '1'],
    ['201606', '1']
];
foreach ($aFech as $fech) {
    $evt->tagFechamentoMes($fech[0], $fech[1]);
}

//########################################################################
$evt->monta();
$evt->assina();

//########################################################################
header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();
