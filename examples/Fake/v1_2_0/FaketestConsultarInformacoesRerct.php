<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\eFinanc\Event;
use NFePHP\eFinanc\Tools;
use NFePHP\eFinanc\Common\FakePretty;
use NFePHP\eFinanc\Common\Soap\SoapFake;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '0_1_2', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_0', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

try {
    //carrega a classe responsavel por lidar com os certificados
    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //usar a classe Fake para não tentar enviar apenas ver o resultado da chamada
    $soap = new SoapFake();
    //desativa a validação da validade do certificado 
    //estamos usando um certificado vencido nesse teste
    $soap->disableCertValidation(true);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadSoapClass($soap);
    
    //executa a consulta
    $std = new stdClass();
    $std->ideventorerct = 1; // 1 ou 2 
    $std->situacaoinformacao = 0; //0-Todas,1-Ativo,2-Retificado,3-Excluído
    $std->numerorecibo = '123456789012345678-12-123-1234-123456789012345678';
    $std->cnpjdeclarante = '99999999999999';
    $std->tipoinscricaodeclarado = 1;
    $std->inscricaodeclarado = '12345678901';
    $std->tipoinscricaotitular = '1';
    $std->inscricaotitular = '12345678901';
    $std->cpfbeneficiariofinal = '12345678901';
    
    $response = $tools->consultar('ConsultarInformacoesRerct', $std);
    
    //retorna os dados que serão usados na conexão para conferência
    echo FakePretty::prettyPrint($response, null);
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
