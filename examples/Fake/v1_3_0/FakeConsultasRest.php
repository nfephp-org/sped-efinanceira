<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\eFinanc\Event;
use NFePHP\eFinanc\Tools;
use NFePHP\eFinanc\Common\Standardize;
use NFePHP\eFinanc\Common\FakePrettyRest;
use NFePHP\eFinanc\Common\Rest\RestFake;

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

    $rest = new RestFake($certificate);
    $rest->disableCertValidation(true);

    //instancia a classe responsável pela comunicação
    $tools = new Tools($configJson, $certificate);
    $tools->loadRestClass($rest);

    //informacoes-cadastrais
    //$resp = $tools->consultarRest('informacoes-cadastrais');

    //lista-efinanceira-movimento
    $filtro = [
        'situacaoInformacao' => 0, //obrigatorio
        //0 – Todas
        //1 – Em Andamento
        //2 – Ativa
        //3 - Retificada
        //4 - Excluída
        'dataInicio' => '01/01/2024', //obrigatorio
        'dataFim' => '01/11/2024' //obrigatorio
    ];
    $resp = $tools->consultarRest('lista-efinanceira-movimento', $filtro);

    //lista-efinanceira-repasse
    $filtro = [
        'situacaoInformacao' => 0, //obrigatorio
        //0 – Todas
        //1 – Em Andamento
        //2 – Ativa
        //3 - Retificada
        //4 - Excluída
        'dataInicio' => '01/01/2024', //obrigatorio
        'dataFim' => '01/11/2024' //obrigatorio
    ];
    //$resp = $tools->consultarRest('lista-efinanceira-repasse', $filtro);

    //informacoes-mov-op-fin
    $filtro = [
        'situacaoInformacao' => 0, //obrigatorio
        //0 – Todas
        //1 – Em Andamento
        //2 – Ativa
        //3 - Retificada
        //4 - Excluída
        'anoMesInicioVigencia' => '01/01/2024', //obrigatorio
        'anoMesTerminoVigencia' => '01/11/2024', //obrigatorio
        'tipoIdentificacao' => '1', //opcional
            //1 - CPF
            //2 - CNPJ
            //3 - NIF Pessoa Física (Número de Identificação Fiscal Pessoa Física)
            //4 - NIF Pessoa Jurídica (Número de Identificação Fiscal Pessoa Jurídica)
            //5 - Passaporte
            //6 - Número do PIS
            //7 - Identidade Mercosul
            //99 - Sem NI
        'identificacao' => '12345678901', //opcional
    ];
    //$resp = $tools->consultarRest('informacoes-mov-op-fin', $filtro);

    //informacoes-mov-op-fin-anual
    $filtro = [
        'situacaoInformacao' => 0, //obrigatorio
        //0 – Todas
        //1 – Em Andamento
        //2 – Ativa
        //3 - Retificada
        //4 - Excluída
        'anoMesInicioVigencia' => '01/01/2024', //obrigatorio
        'anoMesTerminoVigencia' => '01/11/2024', //obrigatorio
        'tipoIdentificacao' => '1', //opcional
        //1 - CPF
        //2 - CNPJ
        //3 - NIF Pessoa Física (Número de Identificação Fiscal Pessoa Física)
        //4 - NIF Pessoa Jurídica (Número de Identificação Fiscal Pessoa Jurídica)
        //5 - Passaporte
        //6 - Número do PIS
        //7 - Identidade Mercosul
        //99 - Sem NI
        'identificacao' => '12345678901', //opcional
    ];
    //$resp = $tools->consultarRest('informacoes-mov-op-fin-anual', $filtro);


    //informacoes-mov-pp
    $filtro = [
        'situacaoInformacao' => 0, //obrigatorio
        //0 – Todas
        //1 – Em Andamento
        //2 – Ativa
        //3 - Retificada
        //4 - Excluída
        'anoMesInicioVigencia' => '01/01/2024', //obrigatorio
        'anoMesTerminoVigencia' => '01/11/2024', //obrigatorio
        'tipoIdentificacao' => '1', //opcional
        //1 - CPF
        //2 - CNPJ
        //3 - NIF Pessoa Física (Número de Identificação Fiscal Pessoa Física)
        //4 - NIF Pessoa Jurídica (Número de Identificação Fiscal Pessoa Jurídica)
        //5 - Passaporte
        //6 - Número do PIS
        //7 - Identidade Mercosul
        //99 - Sem NI
        'identificacao' => '12345678901', //opcional
    ];
    //$resp = $tools->consultarRest('informacoes-mov-pp', $filtro);

    //informacoes-mov-repasse
    $filtro = [
        'situacaoInformacao' => 0, //obrigatorio
        //0 – Todas
        //1 – Em Andamento
        //2 – Ativa
        //3 - Retificada
        //4 - Excluída
        'anoMesInicioVigencia' => '01/01/2024', //obrigatorio
        'anoMesTerminoVigencia' => '01/11/2024', //obrigatorio
        'tipoIdentificacao' => '1', //opcional
        //1 - CPF
        //2 - CNPJ
        //3 - NIF Pessoa Física (Número de Identificação Fiscal Pessoa Física)
        //4 - NIF Pessoa Jurídica (Número de Identificação Fiscal Pessoa Jurídica)
        //5 - Passaporte
        //6 - Número do PIS
        //7 - Identidade Mercosul
        //99 - Sem NI
        'identificacao' => '12345678901', //opcional
    ];
    //$resp = $tools->consultarRest('informacoes-mov-repasse', $filtro);

    //informacoes-intermediario
    $filtro = [
        'giin' => '1234567890123456789', //opcional
        'tipoNi' => '1', //opcional
        'numeroIdentificacao' => '12345678901',
    ];
    //$resp = $tools->consultarRest('informacoes-intermediario', $filtro);

    //informacoes-patrocinado
    $filtro = [
        'cnpjPatrocinado' => '12345678901234', //opcional
        'giinPatrocinado' => '1234567890123456789' //opcional
    ];
    $response = $tools->consultarRest('informacoes-patrocinado', $filtro);

    echo FakePrettyRest::prettyPrint($response);

} catch (\Exception $e) {
    echo $e->getMessage();
}
