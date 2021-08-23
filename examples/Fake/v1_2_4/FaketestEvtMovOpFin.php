<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_4', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->tpni = 2;
$std->tpdeclarado = 'klsks';
$std->nideclarado = 'ssss';
$std->nomedeclarado = 'slkcskkslsklsklsk';
$std->tpnomedeclarado = 'slsklsk';
$std->enderecolivre = 'ssklsklskslks';
$std->tpendereco = 'ssk';
$std->pais = 'BR';
$std->datanasc = '2017-01-01';

$std->nif[0] = new \stdClass();
$std->nif[0]->numeronif = 'skjskjskjs';
$std->nif[0]->paisemissaonif = 'BR';
$std->nif[0]->tpnif = 'slksl';

$std->nomeoutros[0] = new \stdClass();
$std->nomeoutros[0]->nomepf = new \stdClass();
$std->nomeoutros[0]->nomepf->tpnome = 'slsklsk';
$std->nomeoutros[0]->nomepf->prectitulo = 'sss';
$std->nomeoutros[0]->nomepf->titulo = 'slsklsk';
$std->nomeoutros[0]->nomepf->idgeracao = 'sss';
$std->nomeoutros[0]->nomepf->sufixo = 'sss';
$std->nomeoutros[0]->nomepf->gensufixo = 'sss';
$std->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std->nomeoutros[0]->nomepf->primeironome->tipo = 'lsklsk';
$std->nomeoutros[0]->nomepf->primeironome->nome = 'lkdlkdlkd';
$std->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std->nomeoutros[0]->nomepf->meionome[0]->tipo = 'lkslk';
$std->nomeoutros[0]->nomepf->meionome[0]->nome = 'flkfk';
$std->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std->nomeoutros[0]->nomepf->prefixonome->tipo = 'dldkk';
$std->nomeoutros[0]->nomepf->prefixonome->nome = 'flklf';
$std->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std->nomeoutros[0]->nomepf->ultimonome->tipo = 'dddlk';
$std->nomeoutros[0]->nomepf->ultimonome->nome = 'flfkflkf';
$std->nomeoutros[0]->nomepj = new \stdClass();
$std->nomeoutros[0]->nomepj->tpnome = 'dkddkld';
$std->nomeoutros[0]->nomepj->nome = 'ddcldcllc';

$std->infonascimento = new \stdClass();
$std->infonascimento->municipio = 'dcldcldcl';
$std->infonascimento->bairro = 'fflkflkflk';
$std->infonascimento->pais = 'RF';
$std->infonascimento->antigonomepais = 'flkflkfl';

$std->enderecooutros[0] = new \stdClass();
$std->enderecooutros[0]->tpendereco = 'ddlcdld';
$std->enderecooutros[0]->enderecolivre = 'kjdkdj';
$std->enderecooutros[0]->pais = 'BR';

$std->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->enderecooutros[0]->enderecoestrutura->enderecolivre = 'skslkslks';
$std->enderecooutros[0]->enderecoestrutura->cep = '12345678';
$std->enderecooutros[0]->enderecoestrutura->municipio = 'slkslksl';
$std->enderecooutros[0]->enderecoestrutura->uf = 'slkslskslk';
$std->enderecooutros[0]->enderecoestrutura->pais = 'ss';

$std->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'ssjhskhsjsj';
$std->enderecooutros[0]->enderecoestrutura->endereco->numero = 'sss';
$std->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'ssjsjh';
$std->enderecooutros[0]->enderecoestrutura->endereco->andar = 'sss';
$std->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'ssjhsjhsjhs';
$std->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'sskjskj';

$std->paisresid[0] = new \stdClass();
$std->paisresid[0]->pais = 'BR';

$std->paisnacionalidade[0] = new \stdClass();
$std->paisnacionalidade[0]->pais = 'BR';

$std->proprietarios[0] = new \stdClass();
$std->proprietarios[0]->tpni = 1;
$std->proprietarios[0]->niproprietario = 'ssssss';
$std->proprietarios[0]->tpproprietario = 'sslks';
$std->proprietarios[0]->nome = 'skjsksjksj';
$std->proprietarios[0]->tpnome = 'sss';
$std->proprietarios[0]->enderecolivre = 'ssjhsjhsjh';
$std->proprietarios[0]->tpendereco = 'skjsksj';
$std->proprietarios[0]->pais = 'BR';
$std->proprietarios[0]->datanasc = '2017-01-01';

$std->proprietarios[0]->nif[0] = new \stdClass();
$std->proprietarios[0]->nif[0]->numeronif = '1233';
$std->proprietarios[0]->nif[0]->paisemissaonif = 'BR';

$std->proprietarios[0]->nomeoutros[0] = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->tpnome = 'ksksk';
$std->proprietarios[0]->nomeoutros[0]->nomepf->prectitulo = 'iuwiuw';
$std->proprietarios[0]->nomeoutros[0]->nomepf->titulo = 'wwkklwk';
$std->proprietarios[0]->nomeoutros[0]->nomepf->idgeracao = 'kkdkd';
$std->proprietarios[0]->nomeoutros[0]->nomepf->sufixo = 'kjdkdk';
$std->proprietarios[0]->nomeoutros[0]->nomepf->gensufixo = 'jdjdjd';

$std->proprietarios[0]->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->tipo = 'wwwkw';
$std->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->nome = 'lwkwlkw';

$std->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->tipo = 'iwiwiw';
$std->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->nome = 'slksksskks';

$std->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->tipo = 'ssss';
$std->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->nome = 'sksksksk';

$std->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->tipo = 'ssss';
$std->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->nome = 'ksksksk';

$std->proprietarios[0]->enderecooutros[0] = new \stdClass();
$std->proprietarios[0]->enderecooutros[0]->tpendereco = 'skjskj';
$std->proprietarios[0]->enderecooutros[0]->enderecolivre = 'slklsklsklsk';
$std->proprietarios[0]->enderecooutros[0]->pais = 'BR';

$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->enderecolivre = 'ljlkjskjslksj';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->cep = '123456789012';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->municipio = 'sljslkjsksj';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->uf = 'kjskjsksj';

$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'kjskjksjskjsk';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->numero = 'kslksk';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'uiui';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->andar = 'ssss';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'sssssssss';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'ieiei';

$std->proprietarios[0]->paisresid[0] = new \stdClass();
$std->proprietarios[0]->paisresid[0]->pais = 'BR';

$std->proprietarios[0]->paisnacionalidade[0] = new \stdClass();
$std->proprietarios[0]->paisnacionalidade[0]->pais = 'BR';

$std->proprietarios[0]->infonascimento = new \stdClass();
$std->proprietarios[0]->infonascimento->municipio = 'lkslklsk';
$std->proprietarios[0]->infonascimento->bairro = 'klksks';
$std->proprietarios[0]->infonascimento->pais = 'BR';
$std->proprietarios[0]->infonascimento->antigonomepais = 'kjskjskj';

$std->proprietarios[0]->reportavel[0] = new \stdClass();
$std->proprietarios[0]->reportavel[0]->pais = 'BR';

$std->anomescaixa = '201712';

$std->conta[0] = new \stdClass();

$std->conta[0]->medjudic[0] = new \stdClass();
$std->conta[0]->medjudic[0]->numprocjud = '122121211';
$std->conta[0]->medjudic[0]->vara = 33;
$std->conta[0]->medjudic[0]->secjud = 12;
$std->conta[0]->medjudic[0]->subsecjud = 'sklskslk';
$std->conta[0]->medjudic[0]->dtconcessao = '2016-10-10';
$std->conta[0]->medjudic[0]->dtcassacao = '2017-12-05';

$std->conta[0]->infoconta = new \stdClass();
$std->conta[0]->infoconta->tpconta = 'A';
$std->conta[0]->infoconta->subtpconta = 'asa';
$std->conta[0]->infoconta->tpnumconta = 'assss';
$std->conta[0]->infoconta->numconta = 'aasssdddd';
$std->conta[0]->infoconta->tprelacaodeclarado = 1;
$std->conta[0]->infoconta->notitulares = 5;
$std->conta[0]->infoconta->dtencerramentoconta = '2017-12-12';
$std->conta[0]->infoconta->indinatividade = 1;
$std->conta[0]->infoconta->indndoc = 1;
$std->conta[0]->infoconta->totcreditos = 100.00;
$std->conta[0]->infoconta->totdebitos = 800.00;
$std->conta[0]->infoconta->totcreditosmesmatitularidade = 150.00;
$std->conta[0]->infoconta->totdebitosmesmatitularidade = 850.00;
$std->conta[0]->infoconta->vlrultdia = 700.00;

$std->conta[0]->infoconta->reportavel[0] = new \stdClass();
$std->conta[0]->infoconta->reportavel[0]->pais = 'BR';

$std->conta[0]->infoconta->intermediario = new \stdClass();
$std->conta[0]->infoconta->intermediario->giin = '12ASDA.12345.LE.123';
$std->conta[0]->infoconta->intermediario->tpni = 1;
$std->conta[0]->infoconta->intermediario->niintermediario = 'lslsksklsk';

$std->conta[0]->infoconta->fundo = new \stdClass();
$std->conta[0]->infoconta->fundo->giin = '12ASDA.12345.LE.123';
$std->conta[0]->infoconta->fundo->cnpj = '12345678901234';

$std->conta[0]->infoconta->pgtosacum[0] = new \stdClass();
$std->conta[0]->infoconta->pgtosacum[0]->tppgto = 'ksksksk';
$std->conta[0]->infoconta->pgtosacum[0]->totpgtosacum = 154568978.99;

$std->cambio = new \stdClass();
$std->cambio->totcompras = 1245789.35;
$std->cambio->totvendas = 1428974578.88;
$std->cambio->tottransferencias = 152789456.25;

$std->cambio->medjudic[0] = new \stdClass();
$std->cambio->medjudic[0]->numprocjud = '18289192929';
$std->cambio->medjudic[0]->vara = 21;
$std->cambio->medjudic[0]->secjud = 12;
$std->cambio->medjudic[0]->subsecjud = '12 skjskjskj';
$std->cambio->medjudic[0]->dtconcessao = '2017-12-01';
$std->cambio->medjudic[0]->dtcassacao = '2017-10-31';

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtMovOpFin(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Event::f3000($json, $std, $certificate)->toXML();
    //$json = Event::evtMovOpFin($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
