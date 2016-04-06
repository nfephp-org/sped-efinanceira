<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\CadDeclarante;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new CadDeclarante('../config/config.json');

//########################################################################
$id = '000000000000000002';
$indRetificacao = 2; // 1 - para arquivo original 2 - para arquivo de retificação espontânea 3 – para arquivo de retificação a pedido
$tpAmb = 2;
$recibo = '111-11-1111-11';
$evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);

//########################################################################
$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);

//########################################################################
$nome = 'Fulano de Tal';
$endereco = 'Rua Cel Silverio Magalhaes';
$municipio = '3304557';
$uf = 'SP';
$pais = 'BR';
$paisResidencia = 'BR';
$evt->tagInfo($nome, $endereco, $municipio, $uf, $pais, $paisResidencia);

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
header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();
