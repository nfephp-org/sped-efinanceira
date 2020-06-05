<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_3', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->giin = '12ASDA.12345.LE.123';
$std->categoriapatrocinador = 'FATCA601';

$std->infopatrocinado = new \stdClass();
$std->infopatrocinado->giin = '12ASDA.12345.LE.123';
$std->infopatrocinado->cnpj = '12345678901234';
$std->infopatrocinado->nomepatrocinado = 'sjlskjslkjskj';
$std->infopatrocinado->tpnome = 'lks1';

$std->infopatrocinado->nif[0] = new \stdClass();
$std->infopatrocinado->nif[0]->numeronif = 'sa123';
$std->infopatrocinado->nif[0]->paisemissao = 'BR';
$std->infopatrocinado->nif[0]->tpnif = '1111';
$std->infopatrocinado->tpendereco = '1234asd';

$std->infopatrocinado->endereco = new \stdClass();
$std->infopatrocinado->endereco->enderecolivre = 'jlkjksjlskj';
$std->infopatrocinado->endereco->cep = '12345678';
$std->infopatrocinado->endereco->municipio = 'kslksçlks';
$std->infopatrocinado->endereco->pais = 'BR';

$std->infopatrocinado->enderecooutros[0] = new \stdClass();
$std->infopatrocinado->enderecooutros[0]->tpendereco = '1234asd';
$std->infopatrocinado->enderecooutros[0]->enderecolivre = 'kjslksjksj';
$std->infopatrocinado->enderecooutros[0]->pais = 'BR';

$std->infopatrocinado->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->enderecolivre = 'kjskj';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->cep = '12345678';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->municipio = 'skjskjsjks';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->uf = 'Acre';

$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'çlksçksçlks';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->numero = 'jhjh11';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'kwjk';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->andar = '1234';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'skjhsh';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = '111sd';

$std->infopatrocinado->paisresid[0] = new \stdClass();
$std->infopatrocinado->paisresid[0]->pais = 'BR';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtCadPatrocinado(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Event::f2020($json, $std, $certificate)->toXML();
    //$json = Event::evtCadPatrocinado($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
