<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../bootstrap.php';

use NFePHP\eFinanc\Tools;

$tools = new Tools('../config/config.json');

//em geral não é necessário declarar o protocolo de segurança
//porém as vezes a Receita faz alterações em seu servidor e nesse caso 
//o mesmo pode deixar de informar automaticamente qual protocolo usar 
//se isso ocorrer o PHP fica perdido e não completa a comunicação
//se desejar voltar para o ptotocolo automático depois de estabelecer um dos possiveis
//apenas chame o metodo sem passar nenhum parametro
$tools->setSSLProtocol('SSLv3');

//se for passada qualquer parametro será retornadas as possiveis opções 
echo "O protocolos aplicáveis são:<br>";
foreach($tools->getSSLProtocol('1') as $opt) {
    echo $opt."<BR>";
}

//esse método retorna o protocolo de segurança usado na comunicação SSL
//o default é automático
echo "Protocolo SSL definido é : ".$tools->getSSLProtocol()."<BR>";

//em geral não é necessário alterar o tempo de espera para uma resposta
//do servidor durante a cominicação SOAP, mas em alguns casos o servidor
//começa a ficar muito lento e ocorrem os timeouts. Nesses casos e somente
//nesses casos ode ser incrementado o valor do timeout para aumentar o tempo
//de espera por uma resposta
$tools->setSoapTimeOut(10);

//esse método retorna o tempo de timeout registrado pela classe
echo "Tempo de Espera (s): ".$tools->getSoapTimeOut()."<BR>";

//esse método retorna a data de validade do certificado que você está usando
//no formato usado no Brasil 27/04/2016 
echo "Certificado valido até ".$tools->getCertValidity()."<BR>";

//esse metodo retorna o timestamp da data de velidade do certificado
//para que você possa formatar como quizer, por exemplo para o formato necessário 
//a base de dados que está usando. Ex. 2016-04-27
echo "Timestamp de validade do certificado : ".$tools->getCertTimestamp()."<BR>";
