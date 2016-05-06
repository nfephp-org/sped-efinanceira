<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\Abertura;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new Abertura('../config/config.json');

//########################################################################
$id = '1';
$indRetificacao = 1; // 1 - para arquivo original 2 - para arquivo de retificação espontânea 3 – para arquivo de retificação a pedido
$tpAmb = 2;
$recibo = '';
$evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);

//########################################################################
$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);
$evt->tagInfo('2016-03-01', '2016-04-01');

//########################################################################
$cpf = '00431733813';
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

//########################################################################
$cpf = '00431733813';
$setor = 'SEILA ';
$ddd = '11';
$telefone = '11111111';
$ramal = '';
$evt->tagRepresLegal($cpf, $setor, $ddd, $telefone, $ramal);

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
