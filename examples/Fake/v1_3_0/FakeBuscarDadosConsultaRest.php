<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\eFinanc\Event;
use NFePHP\eFinanc\Tools;
use NFePHP\eFinanc\Common\Standardize;
use NFePHP\eFinanc\Common\FakePretty;
use NFePHP\eFinanc\Common\Soap\SoapFake;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_3_0', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

try {
    //carrega a classe responsavel por lidar com os certificados
    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);


    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);

    //informacoes-cadastrais
    //lista-efinanceira-movimento
    //lista-efinanceira-repasse
    //informacoes-mov-op-fin
    //informacoes-mov-op-fin-anual
    //informacoes-mov-pp
    //informacoes-mov-repasse
    //informacoes-intermediario
    //informacoes-patrocinado
    $protocolo = 'sei la o que';
    $resp = $tools->buscarDadosConsultaRest('informacoes-patrocinado', $protocolo);

    echo $resp;

} catch (\Exception $e) {
    echo $e->getMessage();
}
