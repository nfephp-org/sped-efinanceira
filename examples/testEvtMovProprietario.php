<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\MovProprietario;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new MovProprietario('../config/config.json');

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
//PROPRIETÁRIOS
//########################################################################
$tpNI = '1';
$nIProprietario = '99999999999';
$nome = 'Fulano de Tal';
$dataNasc = ' '; 
$endereco = 'Rua das Oliveiras 500';
$pais = 'BR';
$evt->proprietario($tpNI,$nIProprietario,$nome,$dataNasc,$endereco,$pais);
$evt->proprietarioPaisNac($nIProprietario, $pais);
$evt->proprietarioPaisResid($nIProprietario, $pais);

$tpNI = '1';
$nIProprietario = '01234567891';
$nome = 'Ciclano de Tal';
$dataNasc = '1955-12-31'; 
$endereco = 'Av Paulista 4567';
$pais = 'BR';
$evt->proprietario($tpNI,$nIProprietario,$nome,$dataNasc,$endereco,$pais);
$numeroNIF = '1234567890123456789012345';
$paisEmissao = 'US';
$evt->proprietarioNIF($nIProprietario,$numeroNIF, $paisEmissao);
$aPais = ['BR', 'US'];
foreach ($aPais as $pais) {
    $evt->proprietarioPaisNac($nIProprietario, $pais);
}    
$aPais = ['BR', 'US'];
foreach ($aPais as $pais) {
    $evt->proprietarioPaisResid($nIProprietario, $pais);
}
$aPais = ['BR', 'US'];
foreach ($aPais as $pais) {
    $evt->proprietarioReportavel($nIProprietario, $pais);
}

//########################################################################
$evt->monta();

//########################################################################
$evt->assina();

//########################################################################
header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();