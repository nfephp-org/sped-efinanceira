<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_4', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = [];
$std[0] = new \stdClass();
$std[0]->sequencial = '1';
$std[0]->indretificacao = 3;
$std[0]->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std[0]->tpni = 2;
$std[0]->tpdeclarado = 'klsks';
$std[0]->nideclarado = 'ssss';
$std[0]->nomedeclarado = 'slkcskkslsklsklsk';
$std[0]->tpnomedeclarado = 'slsklsk';
$std[0]->enderecolivre = 'ssklsklskslks';
$std[0]->tpendereco = 'ssk';
$std[0]->pais = 'BR';
$std[0]->datanasc = '2017-01-01';

$std[0]->nif[0] = new \stdClass();
$std[0]->nif[0]->numeronif = 'skjskjskjs';
$std[0]->nif[0]->paisemissaonif = 'BR';
$std[0]->nif[0]->tpnif = 'slksl';

$std[0]->nomeoutros[0] = new \stdClass();
$std[0]->nomeoutros[0]->nomepf = new \stdClass();
$std[0]->nomeoutros[0]->nomepf->tpnome = 'slsklsk';
$std[0]->nomeoutros[0]->nomepf->prectitulo = 'sss';
$std[0]->nomeoutros[0]->nomepf->titulo = 'slsklsk';
$std[0]->nomeoutros[0]->nomepf->idgeracao = 'sss';
$std[0]->nomeoutros[0]->nomepf->sufixo = 'sss';
$std[0]->nomeoutros[0]->nomepf->gensufixo = 'sss';
$std[0]->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std[0]->nomeoutros[0]->nomepf->primeironome->tipo = 'lsklsk';
$std[0]->nomeoutros[0]->nomepf->primeironome->nome = 'lkdlkdlkd';
$std[0]->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std[0]->nomeoutros[0]->nomepf->meionome[0]->tipo = 'lkslk';
$std[0]->nomeoutros[0]->nomepf->meionome[0]->nome = 'flkfk';
$std[0]->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std[0]->nomeoutros[0]->nomepf->prefixonome->tipo = 'dldkk';
$std[0]->nomeoutros[0]->nomepf->prefixonome->nome = 'flklf';
$std[0]->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std[0]->nomeoutros[0]->nomepf->ultimonome->tipo = 'dddlk';
$std[0]->nomeoutros[0]->nomepf->ultimonome->nome = 'flfkflkf';
$std[0]->nomeoutros[0]->nomepj = new \stdClass();
$std[0]->nomeoutros[0]->nomepj->tpnome = 'dkddkld';
$std[0]->nomeoutros[0]->nomepj->nome = 'ddcldcllc';

$std[0]->infonascimento = new \stdClass();
$std[0]->infonascimento->municipio = 'dcldcldcl';
$std[0]->infonascimento->bairro = 'fflkflkflk';
$std[0]->infonascimento->pais = 'RF';
$std[0]->infonascimento->antigonomepais = 'flkflkfl';

$std[0]->enderecooutros[0] = new \stdClass();
$std[0]->enderecooutros[0]->tpendereco = 'ddlcdld';
$std[0]->enderecooutros[0]->enderecolivre = 'kjdkdj';
$std[0]->enderecooutros[0]->pais = 'BR';

$std[0]->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std[0]->enderecooutros[0]->enderecoestrutura->enderecolivre = 'skslkslks';
$std[0]->enderecooutros[0]->enderecoestrutura->cep = '12345678';
$std[0]->enderecooutros[0]->enderecoestrutura->municipio = 'slkslksl';
$std[0]->enderecooutros[0]->enderecoestrutura->uf = 'slkslskslk';
$std[0]->enderecooutros[0]->enderecoestrutura->pais = 'ss';

$std[0]->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std[0]->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'ssjhskhsjsj';
$std[0]->enderecooutros[0]->enderecoestrutura->endereco->numero = 'sss';
$std[0]->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'ssjsjh';
$std[0]->enderecooutros[0]->enderecoestrutura->endereco->andar = 'sss';
$std[0]->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'ssjhsjhsjhs';
$std[0]->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'sskjskj';

$std[0]->paisresid[0] = new \stdClass();
$std[0]->paisresid[0]->pais = 'BR';

$std[0]->paisnacionalidade[0] = new \stdClass();
$std[0]->paisnacionalidade[0]->pais = 'BR';

$std[0]->proprietarios[0] = new \stdClass();
$std[0]->proprietarios[0]->tpni = 1;
$std[0]->proprietarios[0]->niproprietario = 'ssssss';
$std[0]->proprietarios[0]->tpproprietario = 'sslks';
$std[0]->proprietarios[0]->nome = 'skjsksjksj';
$std[0]->proprietarios[0]->tpnome = 'sss';
$std[0]->proprietarios[0]->enderecolivre = 'ssjhsjhsjh';
$std[0]->proprietarios[0]->tpendereco = 'skjsksj';
$std[0]->proprietarios[0]->pais = 'BR';
$std[0]->proprietarios[0]->datanasc = '2017-01-01';

$std[0]->proprietarios[0]->nif[0] = new \stdClass();
$std[0]->proprietarios[0]->nif[0]->numeronif = '1233';
$std[0]->proprietarios[0]->nif[0]->paisemissaonif = 'BR';

$std[0]->proprietarios[0]->nomeoutros[0] = new \stdClass();
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf = new \stdClass();
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->tpnome = 'ksksk';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->prectitulo = 'iuwiuw';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->titulo = 'wwkklwk';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->idgeracao = 'kkdkd';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->sufixo = 'kjdkdk';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->gensufixo = 'jdjdjd';

$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->tipo = 'wwwkw';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->nome = 'lwkwlkw';

$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->tipo = 'iwiwiw';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->nome = 'slksksskks';

$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->tipo = 'ssss';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->nome = 'sksksksk';

$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->tipo = 'ssss';
$std[0]->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->nome = 'ksksksk';

$std[0]->proprietarios[0]->enderecooutros[0] = new \stdClass();
$std[0]->proprietarios[0]->enderecooutros[0]->tpendereco = 'skjskj';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecolivre = 'slklsklsklsk';
$std[0]->proprietarios[0]->enderecooutros[0]->pais = 'BR';

$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->enderecolivre = 'ljlkjskjslksj';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->cep = '123456789012';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->municipio = 'sljslkjsksj';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->uf = 'kjskjsksj';

$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'kjskjksjskjsk';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->numero = 'kslksk';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'uiui';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->andar = 'ssss';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'sssssssss';
$std[0]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'ieiei';

$std[0]->proprietarios[0]->paisresid[0] = new \stdClass();
$std[0]->proprietarios[0]->paisresid[0]->pais = 'BR';

$std[0]->proprietarios[0]->paisnacionalidade[0] = new \stdClass();
$std[0]->proprietarios[0]->paisnacionalidade[0]->pais = 'BR';

$std[0]->proprietarios[0]->infonascimento = new \stdClass();
$std[0]->proprietarios[0]->infonascimento->municipio = 'lkslklsk';
$std[0]->proprietarios[0]->infonascimento->bairro = 'klksks';
$std[0]->proprietarios[0]->infonascimento->pais = 'BR';
$std[0]->proprietarios[0]->infonascimento->antigonomepais = 'kjskjskj';

$std[0]->proprietarios[0]->reportavel[0] = new \stdClass();
$std[0]->proprietarios[0]->reportavel[0]->pais = 'BR';

$std[0]->anomescaixa = '201712';

$std[0]->conta[0] = new \stdClass();

$std[0]->conta[0]->medjudic[0] = new \stdClass();
$std[0]->conta[0]->medjudic[0]->numprocjud = '122121211';
$std[0]->conta[0]->medjudic[0]->vara = 33;
$std[0]->conta[0]->medjudic[0]->secjud = 12;
$std[0]->conta[0]->medjudic[0]->subsecjud = 'sklskslk';
$std[0]->conta[0]->medjudic[0]->dtconcessao = '2016-10-10';
$std[0]->conta[0]->medjudic[0]->dtcassacao = '2017-12-05';

$std[0]->conta[0]->infoconta = new \stdClass();
$std[0]->conta[0]->infoconta->tpconta = 'A';
$std[0]->conta[0]->infoconta->subtpconta = 'asa';
$std[0]->conta[0]->infoconta->tpnumconta = 'assss';
$std[0]->conta[0]->infoconta->numconta = 'aasssdddd';
$std[0]->conta[0]->infoconta->tprelacaodeclarado = 1;
$std[0]->conta[0]->infoconta->notitulares = 5;
$std[0]->conta[0]->infoconta->dtencerramentoconta = '2017-12-12';
$std[0]->conta[0]->infoconta->indinatividade = 1;
$std[0]->conta[0]->infoconta->indndoc = 1;
$std[0]->conta[0]->infoconta->totcreditos = 100.00;
$std[0]->conta[0]->infoconta->totdebitos = 800.00;
$std[0]->conta[0]->infoconta->totcreditosmesmatitularidade = 150.00;
$std[0]->conta[0]->infoconta->totdebitosmesmatitularidade = 850.00;
$std[0]->conta[0]->infoconta->vlrultdia = 700.00;

$std[0]->conta[0]->infoconta->reportavel[0] = new \stdClass();
$std[0]->conta[0]->infoconta->reportavel[0]->pais = 'BR';

$std[0]->conta[0]->infoconta->intermediario = new \stdClass();
$std[0]->conta[0]->infoconta->intermediario->giin = '12ASDA.12345.LE.123';
$std[0]->conta[0]->infoconta->intermediario->tpni = 1;
$std[0]->conta[0]->infoconta->intermediario->niintermediario = 'lslsksklsk';

$std[0]->conta[0]->infoconta->fundo = new \stdClass();
$std[0]->conta[0]->infoconta->fundo->giin = '12ASDA.12345.LE.123';
$std[0]->conta[0]->infoconta->fundo->cnpj = '12345678901234';

$std[0]->conta[0]->infoconta->pgtosacum[0] = new \stdClass();
$std[0]->conta[0]->infoconta->pgtosacum[0]->tppgto = 'ksksksk';
$std[0]->conta[0]->infoconta->pgtosacum[0]->totpgtosacum = 154568978.99;

$std[0]->cambio = new \stdClass();
$std[0]->cambio->totcompras = 1245789.35;
$std[0]->cambio->totvendas = 1428974578.88;
$std[0]->cambio->tottransferencias = 152789456.25;

$std[0]->cambio->medjudic[0] = new \stdClass();
$std[0]->cambio->medjudic[0]->numprocjud = '18289192929';
$std[0]->cambio->medjudic[0]->vara = 21;
$std[0]->cambio->medjudic[0]->secjud = 12;
$std[0]->cambio->medjudic[0]->subsecjud = '12 skjskjskj';
$std[0]->cambio->medjudic[0]->dtconcessao = '2017-12-01';
$std[0]->cambio->medjudic[0]->dtcassacao = '2017-10-31';


$std[1] = new \stdClass();
$std[1]->sequencial = '2';
$std[1]->indretificacao = 1;
//$std[1]->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std[1]->tpni = 2;
$std[1]->tpdeclarado = 'klsks';
$std[1]->nideclarado = 'ssss';
$std[1]->nomedeclarado = 'slkcskkslsklsklsk';
$std[1]->tpnomedeclarado = 'slsklsk';
$std[1]->enderecolivre = 'ssklsklskslks';
$std[1]->tpendereco = 'ssk';
$std[1]->pais = 'BR';
$std[1]->datanasc = '2017-01-01';

$std[1]->nif[0] = new \stdClass();
$std[1]->nif[0]->numeronif = 'ksjsksksjsjsj';
$std[1]->nif[0]->paisemissaonif = 'BR';
$std[1]->nif[0]->tpnif = 'slksl';

$std[1]->nomeoutros[0] = new \stdClass();
$std[1]->nomeoutros[0]->nomepf = new \stdClass();
$std[1]->nomeoutros[0]->nomepf->tpnome = 'slsklsk';
$std[1]->nomeoutros[0]->nomepf->prectitulo = 'sss';
$std[1]->nomeoutros[0]->nomepf->titulo = 'slsklsk';
$std[1]->nomeoutros[0]->nomepf->idgeracao = 'sss';
$std[1]->nomeoutros[0]->nomepf->sufixo = 'sss';
$std[1]->nomeoutros[0]->nomepf->gensufixo = 'sss';
$std[1]->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std[1]->nomeoutros[0]->nomepf->primeironome->tipo = 'lsklsk';
$std[1]->nomeoutros[0]->nomepf->primeironome->nome = 'lkdlkdlkd';
$std[1]->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std[1]->nomeoutros[0]->nomepf->meionome[0]->tipo = 'lkslk';
$std[1]->nomeoutros[0]->nomepf->meionome[0]->nome = 'flkfk';
$std[1]->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std[1]->nomeoutros[0]->nomepf->prefixonome->tipo = 'dldkk';
$std[1]->nomeoutros[0]->nomepf->prefixonome->nome = 'flklf';
$std[1]->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std[1]->nomeoutros[0]->nomepf->ultimonome->tipo = 'dddlk';
$std[1]->nomeoutros[0]->nomepf->ultimonome->nome = 'flfkflkf';
$std[1]->nomeoutros[0]->nomepj = new \stdClass();
$std[1]->nomeoutros[0]->nomepj->tpnome = 'dkddkld';
$std[1]->nomeoutros[0]->nomepj->nome = 'ddcldcllc';

$std[1]->infonascimento = new \stdClass();
$std[1]->infonascimento->municipio = 'dcldcldcl';
$std[1]->infonascimento->bairro = 'fflkflkflk';
$std[1]->infonascimento->pais = 'RF';
$std[1]->infonascimento->antigonomepais = 'flkflkfl';

$std[1]->enderecooutros[0] = new \stdClass();
$std[1]->enderecooutros[0]->tpendereco = 'ddlcdld';
$std[1]->enderecooutros[0]->enderecolivre = 'kjdkdj';
$std[1]->enderecooutros[0]->pais = 'BR';

$std[1]->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std[1]->enderecooutros[0]->enderecoestrutura->enderecolivre = 'skslkslks';
$std[1]->enderecooutros[0]->enderecoestrutura->cep = '12345678';
$std[1]->enderecooutros[0]->enderecoestrutura->municipio = 'slkslksl';
$std[1]->enderecooutros[0]->enderecoestrutura->uf = 'slkslskslk';
$std[1]->enderecooutros[0]->enderecoestrutura->pais = 'ss';

$std[1]->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std[1]->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'ssjhskhsjsj';
$std[1]->enderecooutros[0]->enderecoestrutura->endereco->numero = 'sss';
$std[1]->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'ssjsjh';
$std[1]->enderecooutros[0]->enderecoestrutura->endereco->andar = 'sss';
$std[1]->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'ssjhsjhsjhs';
$std[1]->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'sskjskj';

$std[1]->paisresid[0] = new \stdClass();
$std[1]->paisresid[0]->pais = 'BR';

$std[1]->paisnacionalidade[0] = new \stdClass();
$std[1]->paisnacionalidade[0]->pais = 'BR';

$std[1]->proprietarios[0] = new \stdClass();
$std[1]->proprietarios[0]->tpni = 1;
$std[1]->proprietarios[0]->niproprietario = 'ssssss';
$std[1]->proprietarios[0]->tpproprietario = 'sslks';
$std[1]->proprietarios[0]->nome = 'skjsksjksj';
$std[1]->proprietarios[0]->tpnome = 'sss';
$std[1]->proprietarios[0]->enderecolivre = 'ssjhsjhsjh';
$std[1]->proprietarios[0]->tpendereco = 'skjsksj';
$std[1]->proprietarios[0]->pais = 'BR';
$std[1]->proprietarios[0]->datanasc = '2017-01-01';

$std[1]->proprietarios[0]->nif[0] = new \stdClass();
$std[1]->proprietarios[0]->nif[0]->numeronif = '1233';
$std[1]->proprietarios[0]->nif[0]->paisemissaonif = 'BR';

$std[1]->proprietarios[0]->nomeoutros[0] = new \stdClass();
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf = new \stdClass();
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->tpnome = 'ksksk';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->prectitulo = 'iuwiuw';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->titulo = 'wwkklwk';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->idgeracao = 'kkdkd';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->sufixo = 'kjdkdk';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->gensufixo = 'jdjdjd';

$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->tipo = 'wwwkw';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->nome = 'lwkwlkw';

$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->tipo = 'iwiwiw';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->nome = 'slksksskks';

$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->tipo = 'ssss';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->nome = 'sksksksk';

$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->tipo = 'ssss';
$std[1]->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->nome = 'ksksksk';

$std[1]->proprietarios[0]->enderecooutros[0] = new \stdClass();
$std[1]->proprietarios[0]->enderecooutros[0]->tpendereco = 'skjskj';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecolivre = 'slklsklsklsk';
$std[1]->proprietarios[0]->enderecooutros[0]->pais = 'BR';

$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->enderecolivre = 'ljlkjskjslksj';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->cep = '123456789012';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->municipio = 'sljslkjsksj';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->uf = 'kjskjsksj';

$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'kjskjksjskjsk';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->numero = 'kslksk';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'uiui';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->andar = 'ssss';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'sssssssss';
$std[1]->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'ieiei';

$std[1]->proprietarios[0]->paisresid[0] = new \stdClass();
$std[1]->proprietarios[0]->paisresid[0]->pais = 'BR';

$std[1]->proprietarios[0]->paisnacionalidade[0] = new \stdClass();
$std[1]->proprietarios[0]->paisnacionalidade[0]->pais = 'BR';

$std[1]->proprietarios[0]->infonascimento = new \stdClass();
$std[1]->proprietarios[0]->infonascimento->municipio = 'lkslklsk';
$std[1]->proprietarios[0]->infonascimento->bairro = 'klksks';
$std[1]->proprietarios[0]->infonascimento->pais = 'BR';
$std[1]->proprietarios[0]->infonascimento->antigonomepais = 'kjskjskj';

$std[1]->proprietarios[0]->reportavel[0] = new \stdClass();
$std[1]->proprietarios[0]->reportavel[0]->pais = 'BR';

$std[1]->anomescaixa = '201712';

$std[1]->conta[0] = new \stdClass();

$std[1]->conta[0]->medjudic[0] = new \stdClass();
$std[1]->conta[0]->medjudic[0]->numprocjud = '122121211';
$std[1]->conta[0]->medjudic[0]->vara = 33;
$std[1]->conta[0]->medjudic[0]->secjud = 12;
$std[1]->conta[0]->medjudic[0]->subsecjud = 'sklskslk';
$std[1]->conta[0]->medjudic[0]->dtconcessao = '2016-10-10';
$std[1]->conta[0]->medjudic[0]->dtcassacao = '2017-12-05';

$std[1]->conta[0]->infoconta = new \stdClass();
$std[1]->conta[0]->infoconta->tpconta = 'A';
$std[1]->conta[0]->infoconta->subtpconta = 'asa';
$std[1]->conta[0]->infoconta->tpnumconta = 'assss';
$std[1]->conta[0]->infoconta->numconta = 'aasssdddd';
$std[1]->conta[0]->infoconta->tprelacaodeclarado = 1;
$std[1]->conta[0]->infoconta->notitulares = 5;
$std[1]->conta[0]->infoconta->dtencerramentoconta = '2017-12-12';
$std[1]->conta[0]->infoconta->indinatividade = 1;
$std[1]->conta[0]->infoconta->indndoc = 1;
$std[1]->conta[0]->infoconta->totcreditos = 100.00;
$std[1]->conta[0]->infoconta->totdebitos = 800.00;
$std[1]->conta[0]->infoconta->totcreditosmesmatitularidade = 150.00;
$std[1]->conta[0]->infoconta->totdebitosmesmatitularidade = 850.00;
$std[1]->conta[0]->infoconta->vlrultdia = 700.00;

$std[1]->conta[0]->infoconta->reportavel[0] = new \stdClass();
$std[1]->conta[0]->infoconta->reportavel[0]->pais = 'BR';

$std[1]->conta[0]->infoconta->intermediario = new \stdClass();
$std[1]->conta[0]->infoconta->intermediario->giin = '12ASDA.12345.LE.123';
$std[1]->conta[0]->infoconta->intermediario->tpni = 1;
$std[1]->conta[0]->infoconta->intermediario->niintermediario = 'lslsksklsk';

$std[1]->conta[0]->infoconta->fundo = new \stdClass();
$std[1]->conta[0]->infoconta->fundo->giin = '12ASDA.12345.LE.123';
$std[1]->conta[0]->infoconta->fundo->cnpj = '12345678901234';

$std[1]->conta[0]->infoconta->pgtosacum[0] = new \stdClass();
$std[1]->conta[0]->infoconta->pgtosacum[0]->tppgto = 'ksksksk';
$std[1]->conta[0]->infoconta->pgtosacum[0]->totpgtosacum = 154568978.99;

$std[1]->cambio = new \stdClass();
$std[1]->cambio->totcompras = 1245789.35;
$std[1]->cambio->totvendas = 1428974578.88;
$std[1]->cambio->tottransferencias = 152789456.25;

$std[1]->cambio->medjudic[0] = new \stdClass();
$std[1]->cambio->medjudic[0]->numprocjud = '18289192929';
$std[1]->cambio->medjudic[0]->vara = 21;
$std[1]->cambio->medjudic[0]->secjud = 12;
$std[1]->cambio->medjudic[0]->subsecjud = '12 skjskjskj';
$std[1]->cambio->medjudic[0]->dtconcessao = '2017-12-01';
$std[1]->cambio->medjudic[0]->dtcassacao = '2017-10-31';


try {
    $xmls = [];
    //cria o evento e retorna o XML assinado
    $xmls[0] = Event::evtMovOpFin($configJson,$std[0])->toXml();
    $xmls[1] = Event::evtMovOpFin($configJson,$std[1])->toXml();

    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = false;
    $dom->preserveWhiteSpace = false;
    $dom->loadXML($xmls[0]); //este será o xml base, os demais serão incorporados a este

    foreach($xmls as $key => $xml) {
        if ($key === 0) {
            continue;
        }
        $dom1 = new \DOMDocument('1.0', 'UTF-8');
        $dom1->formatOutput = false;
        $dom1->preserveWhiteSpace = false;
        $dom1->loadXML($xml);
        $node1 = $dom1->getElementsByTagName('evtMovOpFin')->item(0);
        $node = $dom->importNode($node1, true);
        $dom->documentElement->appendChild($node);
        $dom1 = null;
    }
    $xml = $dom->saveXML();
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;

} catch (\Exception $e) {
    echo $e->getMessage();
}
