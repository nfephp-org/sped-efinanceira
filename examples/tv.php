<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\Abertura;

$evt = new Abertura('../config/config.json');

$id = '000000000000000001';
// 1 - para arquivo original
// 2 - para arquivo de retificação espontânea
// 3 – para arquivo de retificação a pedido
$indRetificacao = 1; 
$tpAmb = 2;

$evt->tagAbertura($id, $indRetificacao, $tpAmb);

$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);
$evt->tagInfo('2016-03-01', '2016-04-01');

$cpf = '9999999999999';
$nome = 'Fulano de Tal';
$setor = 'SETOR XXXX';
$ddd = '11';
$telefone = '50734858';
$ramal = '';
$logradouro = 'Rua Cel Silverio Magalhaes';
$numero = '346';
$complemento = '';
$bairro = 'Jardim da Saude';
$cep = '04154000';
$municipio = 'Sao Paulo';
$uf = 'SP';
$evt->tagResponsavelRMF($cpf, $nome, $setor, $ddd, $telefone, $ramal, $logradouro, $numero, $complemento, $bairro, $cep, $municipio, $uf);

$cpf = '11111111111111';
$setor = 'SEILA ';
$ddd = '11';
$telefone = '11111111';
$evt->tagRepresLegal($cpf, $setor, $ddd, $telefone);

$evt->monta();
$evt->assina();

header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();
