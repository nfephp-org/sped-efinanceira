<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\Common\Certificate;
use JsonSchema\Validator;
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

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 2;
//$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->dtinicio = '2017-01-01'; //A data informada deve pertencer ao mesmo semestre da dtFim
$std->dtfim = '2017-05-31';

//$std->aberturapp = new \stdClass();
//$std->aberturapp->tpempresa[0] = new \stdClass();
//$std->aberturapp->tpempresa[0]->tpprevpriv = 'X';

$std->aberturamovopfin = new \stdClass();
$std->aberturamovopfin->responsavelrmf = new \stdClass();
$std->aberturamovopfin->responsavelrmf->cpf = '12345678901';
$std->aberturamovopfin->responsavelrmf->nome = 'lkslsklsklskslksl';
$std->aberturamovopfin->responsavelrmf->setor = 'lkslsklsk';
$std->aberturamovopfin->responsavelrmf->telefone = new \stdClass();
$std->aberturamovopfin->responsavelrmf->telefone->ddd = '11';
$std->aberturamovopfin->responsavelrmf->telefone->numero = '5555555';
$std->aberturamovopfin->responsavelrmf->telefone->ramal = '123';

$std->aberturamovopfin->responsavelrmf->endereco = new \stdClass();
$std->aberturamovopfin->responsavelrmf->endereco->logradouro = 'jhskhjskjhsjshjh';
$std->aberturamovopfin->responsavelrmf->endereco->numero = 'km123';
$std->aberturamovopfin->responsavelrmf->endereco->complemento = 'lkwlkwlkw';
$std->aberturamovopfin->responsavelrmf->endereco->bairro = 'jdkjdkjd';
$std->aberturamovopfin->responsavelrmf->endereco->cep = '12345678';
$std->aberturamovopfin->responsavelrmf->endereco->municipio = 'lksklsk';
$std->aberturamovopfin->responsavelrmf->endereco->uf = 'AC';

$std->aberturamovopfin->respefin[1] = new \stdClass();
$std->aberturamovopfin->respefin[1]->cpf = '12345678901';
$std->aberturamovopfin->respefin[1]->nome = 'lkslsklsklskslksl';
$std->aberturamovopfin->respefin[1]->setor = 'lkslsklsk';
$std->aberturamovopfin->respefin[1]->email = 'ksksk@msmsl.com';
$std->aberturamovopfin->respefin[1]->telefone = new \stdClass();
$std->aberturamovopfin->respefin[1]->telefone->ddd = '11';
$std->aberturamovopfin->respefin[1]->telefone->numero = '5555555';
$std->aberturamovopfin->respefin[1]->telefone->ramal = '123';

$std->aberturamovopfin->respefin[1]->endereco = new \stdClass();
$std->aberturamovopfin->respefin[1]->endereco->logradouro = 'jhskhjskjhsjshjh';
$std->aberturamovopfin->respefin[1]->endereco->numero = 'km123';
$std->aberturamovopfin->respefin[1]->endereco->complemento = 'lkwlkwlkw';
$std->aberturamovopfin->respefin[1]->endereco->bairro = 'jdkjdkjd';
$std->aberturamovopfin->respefin[1]->endereco->cep = '12345678';
$std->aberturamovopfin->respefin[1]->endereco->municipio = 'lksklsk';
$std->aberturamovopfin->respefin[1]->endereco->uf = 'AC';

$std->aberturamovopfin->represlegal = new \stdClass();
$std->aberturamovopfin->represlegal->cpf = '12345678901';
$std->aberturamovopfin->represlegal->setor = 'lkslsklsk';
$std->aberturamovopfin->represlegal->telefone = new \stdClass();
$std->aberturamovopfin->represlegal->telefone->ddd = '11';
$std->aberturamovopfin->represlegal->telefone->numero = '5555555';
$std->aberturamovopfin->represlegal->telefone->ramal = '123';

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
    
    $evento = Event::evtAberturaeFinanceira($configJson, $std);
    
    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    //carrega a classe responsável pelo envio SOAP
    //nesse caso um envio falso
    $tools->loadSoapClass($soap);
    
    //executa o envio
    $response = $tools->enviar([$evento], $tools::MODO_NORMAL);
    
    //retorna os dados que serão usados na conexão para conferência
    echo FakePretty::prettyPrint($response, '');
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
