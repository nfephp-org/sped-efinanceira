<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_3_0', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';

$std->infocadastro = new \stdClass();
$std->infocadastro->giin = '12ASDA.12345.LE.123';
$std->infocadastro->categoriadeclarante = 'FATCA601';
$std->infocadastro->nome = 'lalalalalalalal';
$std->infocadastro->tpnome = 'OECD202';
$std->infocadastro->enderecolivre = 'lkjslksjlksjlskjlskjs';
$std->infocadastro->tpendereco = 'OECD305';
$std->infocadastro->municipio = '1100015';
$std->infocadastro->uf = 'AC';
$std->infocadastro->cep = '12345678';
$std->infocadastro->pais = 'BR';

//NOVO CAMPO até 3 repetições Informações do tipos de instituições de pagamento operados pelo declarante
$std->infocadastro->infotpinstpgto[0] = new \stdClass(); //opcional
$std->infocadastro->infotpinstpgto[0]->tpinstpgto = '1';
//1 - Emissor de Instrumento de Pagamento Pos- Pago: Entidade que gerencia contas de pagamento do tipo pós-pagas, na qual os recursos são depositados pelo declarado para pagamento de débitos já assumidos.
//2 - Credenciador: Instituição de pagamento que credencia a aceitação de instrumento de pagamento.
//3 - Sub-credenciador: O participante do arranjo de pagamento que habilita usuário final recebedor para a aceitação de instrumento de pagamento

$std->infocadastro->nif[0] = new \stdClass();
$std->infocadastro->nif[0]->numeronif = '828928';
$std->infocadastro->nif[0]->paisemissao = 'BR';
$std->infocadastro->nif[0]->tpnif = 'TIF';

$std->infocadastro->paisresid[0] = new \stdClass();
$std->infocadastro->paisresid[0]->pais = 'BR';

$std->infocadastro->enderecooutros[0] = new \stdClass();
$std->infocadastro->enderecooutros[0]->tpendereco = 'OECD305';
$std->infocadastro->enderecooutros[0]->enderecolivre = 'wuyieuyieuywuyieiuw';
$std->infocadastro->enderecooutros[0]->pais = 'BR';

$std->infocadastro->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->infocadastro->enderecooutros[0]->enderecoestrutura->enderecolivre = 'ueueueieueueueu';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->cep = '123455678';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->municipio = 'ksjksjksjksjks';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->uf = 'AC';

$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'sksçlkslskl';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco->numero = 'ksksk1';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'kjslkjskj';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco->andar = '222as';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'skslkslkslks';
$std->infocadastro->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = '1234567';

try {

   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);

    //cria o evento e retorna o XML assinado
    $xml = Event::evtCadDeclarante(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();

    //$xml = Event::f2000($json, $std, $certificate)->toXML();
    //$json = Event::evtCadDeclarante($configjson, $std, $certificate)->toJson();

    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
