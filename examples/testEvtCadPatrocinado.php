<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\CadPatrocinado;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new CadPatrocinado('../config/config.json');

//########################################################################
$id = '000000000000000001';
$indRetificacao = 1; // 1 - para arquivo original 2 - para arquivo de retificação espontânea 3 – para arquivo de retificação a pedido
$tpAmb = 2;
$recibo = '';
$evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);

//########################################################################
$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);

//########################################################################
$giin = "0123456789123456789";
$cnpj = "11119090910270";
$nome = 'Fulano de Tal';
$endereco = 'Rua Cel Silverino Fonseca';
$municipio = '3304557';
$pais = 'BR';
$paisResidencia = 'BR';
$evt->tagInfo($giin, $cnpj, $nome, $endereco, $municipio, $pais, $paisResidencia);

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
