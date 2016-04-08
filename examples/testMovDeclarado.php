<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\MovDeclarado;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new MovDeclarado('../config/config.json');

//########################################################################
$id = '000000000000000001';
$indRetificacao = 1;
$tpAmb = 2;
$recibo = '';
$evt->tagEvento($id, $indRetificacao, $tpAmb, $recibo);

//########################################################################
$cnpj = '99999090910270';
$evt->tagDeclarante($cnpj);

//########################################################################
//DECLARADO
//########################################################################
$tpNI = '2';
$nIDeclarado = '01234567891234';
$nomeDeclarado = 'Nome do Declarado';
$dataNasc = '1965-05-12';
$enderecoLivre = 'EnderecoLivre com 200 caracteres';
$pais = 'BR';
$evt->declarado($tpNI, $nIDeclarado, $nomeDeclarado, $dataNasc, $enderecoLivre, $pais);

$aNIF = [
  ['1234567890123456789012345', 'US'],
  ['987989979798989887', 'AR'],
  ['3698978999990000000111', 'GB']  
];
foreach ($aNIF as $nif) {
    $numeroNIF = $nif[0];
    $paisEmissao = $nif[1];
    $evt->declaradoNIF($numeroNIF, $paisEmissao);
}
$aPais = ['BR'];
foreach ($aPais as $pais) {
    $evt->declaradoPaisNac($pais);
}    
$aPais = ['BR'];
foreach ($aPais as $pais) {
    $evt->declaradoPaisResid($pais);
}    
$aTpDec = ['FATCA101'];
foreach ($aTpDec as $tpDeclarado) {
    $evt->declaradoTipo($tpDeclarado);
}

//########################################################################
$evt->monta();

//########################################################################
$evt->assina();

//########################################################################
header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();
