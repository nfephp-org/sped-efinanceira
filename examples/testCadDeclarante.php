<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\CadDeclarante;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new CadDeclarante('../config/config.json');

//########################################################################
$id = '000000000000000001';
$indRetificacao = 1; // 1 - para arquivo original 2 - para arquivo de retificação espontânea 3 – para arquivo de retificação a pedido
$tpAmb = 2;
$recibo = '';
$evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);

//########################################################################
$cnpj = '07959165700012';
$evt->tagDeclarante($cnpj);

//########################################################################
$giin = '';//caso não exista deixe em branco
$nome = 'Actuary Corretora de seguros e consult atuarial';
$endereco = 'Av. Pres. Kennedy, 2999';
$municipio = '4106902';
$uf = 'PR';
$pais = 'BR';
$paisResidencia = 'BR';
$evt->tagInfo($giin, $nome, $endereco, $municipio, $uf, $pais, $paisResidencia);

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
