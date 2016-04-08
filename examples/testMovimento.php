<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Factory\Movimento;

//########################################################################
//instancia a classe e passa o arquivo de configuração
$evt = new Movimento('../config/config.json');

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
//Movimento
//########################################################################
$anomes = '201603';
$evt->movAnoMes($anomes);

//DADOS DA CONTA 1
$numConta = '1234|123|1234567890123';
$tpConta = '1';
$subTpConta = '101';
$tpNumConta = 'OECD605';
$tpRelacaoDeclarado = '3';
$noTitulares = '1';
$dtEncerramentoConta = '';
$evt->conta($numConta,$tpConta,$subTpConta,$tpNumConta,$tpRelacaoDeclarado,$noTitulares,$dtEncerramentoConta);

$totCreditos = '101454,52';
$totDebitos = '9992,22';
$totCreditosMesmaTitularidade = '10000,00';
$totDebitosMesmaTitularidade = '10000,00';
$vlrUltDia = '58045,00';
$evt->contaBalanco($numConta,$totCreditos,$totDebitos,$totCreditosMesmaTitularidade,$totDebitosMesmaTitularidade,$vlrUltDia);

$evt->contaReportavel($numConta, 'US');

$tpPgto = ['FATCA503','FATCA502','FATCA501'];
$totPgtosAcum = '15487,88';
$evt->contaPgtosAcum($numConta, $tpPgto, $totPgtosAcum);

$giin = '16546546879887878999';
$tpNI = '1';
$nIIntermediario = '514789154'; 
$evt->contaIntermediario($numConta, $giin, $tpNI, $nIIntermediario);

$numProcJud = '123456789';
$vara = '111';
$secJud = '11';
$subSecJud = '1111';
$dtConcessao = '2011-11-11';
$dtCassacao = '';
$evt->contaMedJudic($numConta,$numProcJud,$vara,$secJud,$subSecJud,$dtConcessao,$dtCassacao);

$numProcJud = '222222222';
$vara = '222';
$secJud = '22';
$subSecJud = '22222';
$dtConcessao = '2012-12-12';
$dtCassacao = '';
$evt->contaMedJudic($numConta,$numProcJud,$vara,$secJud,$subSecJud,$dtConcessao,$dtCassacao);


//DADOS DA CONTA 2
$numConta = 'ABD12345';
$tpConta = '1';
$subTpConta = '999';
$tpNumConta = 'OXXXX';
$tpRelacaoDeclarado = '3';
$noTitulares = '2';
$dtEncerramentoConta = '';
$evt->conta($numConta,$tpConta,$subTpConta,$tpNumConta,$tpRelacaoDeclarado,$noTitulares,$dtEncerramentoConta);

$totCreditos = '2589786,28';
$totDebitos = '158743,96';
$totCreditosMesmaTitularidade = '0,00';
$totDebitosMesmaTitularidade = '0,00';
$vlrUltDia = '2466121,65';
$evt->contaBalanco($numConta,$totCreditos,$totDebitos,$totCreditosMesmaTitularidade,$totDebitosMesmaTitularidade,$vlrUltDia);

$tpPgto = ['FATCA111'];
$totPgtosAcum = '90,00';
$evt->contaPgtosAcum($numConta, $tpPgto, $totPgtosAcum);

$giin = '656598984544444';
$cnpj = '55512325469841';
$evt->contaFundo($numConta, $giin, $cnpj);


//OPERAÇÕES DE CAMBIO
$totCompras = '1508745,66';
$totVendas = '58987,01';
$totTransferencias = '122879,11';
$evt->cambio($totCompras, $totVendas, $totTransferencias);

$numProcJud = '1234567890';
$vara = '111';
$secJud = '0202';
$subSecJud = '23';
$dtConcessao = '2015-12-31';
$dtCassacao = '';
$evt->cambioMedJudic($totTransferencias, $totVendas, $totCompras, $totPgtosAcum, $vlrUltDia, $totDebitosMesmaTitularidade);


//########################################################################
$evt->monta();

//########################################################################
$evt->assina();

//########################################################################
header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();