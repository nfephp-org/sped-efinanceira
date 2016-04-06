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
//Neste campo deve ser preenchido o tipo de Número de Identificação (NI) do declarado, de acordo com a Tabela de Tipos de
//NI. Os Tipos de NI 3 (NIF de Pessoa Física), 4 (NIF de Pessoa Jurídica), 5 (Passaporte) e 6 (Número do PIS) só devem ser
//informados na impossibilidade de se obter o CPF ou o CNPJ do declarado. Em relação ao Tipo de NI 6 (Número do PIS), este só
//deve ser informado caso o declarado só possua conta de Fundo de Garantia por Tempo de Serviço (FGTS) na declarante e não
//seja possível obter seu CPF ou CNPJ de maneira inequívoca. O NI do declarado deverá ser preenchido no campo 14 deste evento
//(NIDeclarado).
//1|CPF 2|CNPJ 3|NIF Pessoa Fisica 4|NIF Pessoa Jurddica 5|Passaporte 6|Numero do PIS 7|Identidade Mercosul 99|Sem NI
$tpNI = '';

//Este campo só precisa ser preenchido caso o declarado possua alguma de suas contas marcada como reportável para um
//país diferente de “BR”, ou seja, caso a conta seja objeto de reporte a algum país estrangeiro, por força de acordo de troca de
//informações. Nesse caso, preencher com o valor correspondente na tabela “Tipo de Declarado”, vigente na data de recepção do
//evento. Se alguma das contas do declarado for reportável aos Estados Unidos, o campo deve ser preenchido obrigatoriamente
//com um dos seguintes valores: FATCA101, FATCA102, FATCA103, FATCA104 ou FATCA105. A classificação em relação a qual
//dos valores deve ser utilizado compete à entidade declarante, de acordo com a interpretação do tratado aplicável ao caso.
$tpDeclarado = [];//pode ser um array vazio ou conter vários codigos. só deve ser indicado quando for reportável 

//Preencher com o número de identificação (NI) do declarado, de acordo com o tipo informado no campo 12 (tpNI). Nos casos
//de CPF e CNPJ, o número será validado internamente de acordo com as informações existentes nos respectivos cadastros da
//Receita Federal do Brasil. Não será validada situação cadastral do declarado (apenas existência nos cadastros CPF ou CNPJ). O
//NI deve ser informado sem máscara (separadores de “.” e “–“ do CPF e CNPJ), mas incluindo o dígito verificador.
$nIDeclarado = '';

//Preencher com o(s) Número(s) de Identificação Fiscal (NIF) no exterior, quando houver, ou quando for informado o tipo de NI
//(tpNI) correspondente a 3 (NIF de Pessoa Física) ou 4 (NIF de Pessoa Jurídica). Nesses últimos dois casos, o NIF do declarado
//deverá constar tanto no campo NIDeclarado (campo 14) quanto no campo NúmeroNIF (campo 16).
//O Número de Identificação Fiscal no exterior é o número que identifica o declarado como contribuinte em determinado Estado
//estrangeiro. No caso dos Estados Unidos, o SSN (Social Security Number) para as pessoas físicas e o EIN (Employer Identification
//Number) para as pessoas jurídicas são bastante utilizados.
//Para NIFs emitidos por países da União Europeia, o sítio “TIN on Europa” (https://ec.europa.eu/taxation_customs/tin/) da
//Comissão Europeia pode auxiliar na verificação acerca da validade de um número de identificação fiscal informado pelo declarado.
//Alguns países não adotam um NIF para seus contribuintes. Outros não possuem um número único de identificação fiscal.
//Exemplos de situações:
//1) Declarado possui CPF e não possui NIF:
//tpNI = 1 (CPF)
//NIDeclarado = número do CPF
//NIF – Não informar
//2) Declarado não possui CPF, mas possui NIF
//tpNI = 3
//NIDeclarado = número do NIF
//NIF = Preencher o mesmo número do NIF informado no campo NIDeclarado e o seu país de emissão, nos campos 16 e 17,
//respectivamente.
//3) Declarado possui CPF e possui três NIFs:
//tpNI = 1 (CPF)
//NIDeclarado = número do CPF
//NIF 1 = Preencher o número do NIF 1 e seu respectivo país de emissão nos campos 16 e 17.
//NIF 2 = Informar outro grupo NIF (campo 15) com número do NIF 2 e seu respectivo país de emissão nos campos 16 e 17.
//NIF 3 = Informar outro grupo NIF (campo 15) com número do NIF 3 e seu respectivo país de emissão nos campos 16 e 17.
//A mesma lógica se aplica para CNPJ e NIF de Pessoa Jurídica.
$nIIF = [
    ['numero'=>'', 'pais'=>'']
];

//Preencher com o nome completo (em caso de pessoa física), razão social, nome empresarial ou denominação (em caso de
//pessoa jurídica) do declarado, sendo vedado o uso de caracteres especiais.
$nomeDeclarado = '';

//Preencher com a data de nascimento do declarado, quando disponível, no formato AAAA-MM-DD
$dataNasc = '';

//Neste campo deve ser preenchido endereço do declarado, em formato livre, sendo vedado o uso de caracteres especiais.
//Este campo tem preenchimento obrigatório sempre que o declarado figurar na condição de titular de alguma “conta” (conforme
//conceito descrito no campo 47 deste Evento) na entidade declarante (tpRelaçãoDeclarado = 1 – Titular, no campo 62) ou se
//alguma de suas “contas” for reportável para os Estados Unidos no evento (campo 57 preenchido com “US”). O endereço a ser
//preenchido é o endereço para correspondência cadastrado para o declarado junto à entidade declarante. Na hipótese de múltiplos
//endereços, utilizar o endereço principal de relacionamento do declarado com a entidade declarante
$enderecoLivre = '';

//Neste campo deve ser preenchido o código do país do endereço do declarado, de acordo com a Tabela de Países vigente na
//data de recepção do evento.
$pais = '';

//Neste grupo deve(m) constar o(s) código(s) do(s) país(es) de residência fiscal do declarado, quando disponível(is), de acordo
//com a Tabela de Países vigente na data de recepção do evento.
$paisResid = '';

//Neste campo deve ser preenchido o código do país de nacionalidade do declarado, quando disponível, de acordo com a
//Tabela de Países vigente na data de recepção do evento.
$paisNacionalidade = '';

$evt->ideDeclarado($tpNI, $tpDeclarado, $nIDeclarado, $nIIF, $nomeDeclarado, $dataNasc, $enderecoLivre, $pais, $paisResid, $paisNacionalidade);

//########################################################################
//Nesse campo deve ser preenchido o tipo de NI (número de identificação) do “proprietário” (conforme conceito descrito no
//campo 27 deste Evento) que está sendo informado, de acordo com a classificação descrita na Tabela Tipos de NI vigente na data
//de recepção do Evento. Os únicos valores válidos são 1 = CPF; 3 = NIF de Pessoa Física ou 5 = Passaporte, na impossibilidade
//de obtenção dos dois primeiros. Este campo qualifica o NI que está sendo informado no campo 29 deste Evento.
$tpNI = '';

//Preencher com o NI (número de identificação) do “proprietário” (conforme conceito descrito no campo 27 deste Evento) que
//está sendo informado.
$nIProprietario = '';

//Preencher com o(s) Número(s) de Identificação Fiscal (NIF) no exterior, quando houver, ou quando for informado o tipo de NI
//(tpNI) correspondente a 3 (NIF de Pessoa Física). Nesse caso, o NIF do “proprietário” (conforme conceito descrito no campo 27
//deste Evento) deverá constar tanto no campo NIProprietario (campo 29) quanto no campo NúmeroNIF (campo 31).
//O Número de Identificação Fiscal no exterior é o número que identifica o “proprietário” como contribuinte em determinado
//Estado estrangeiro. No caso dos Estados Unidos, o SSN (Social Security Number) para as pessoas físicas e o EIN (Employer
//Identification Number) para as pessoas jurídicas, são bastante utilizados.
//Para NIFs emitidos por países da União Europeia, o sítio “TIN on Europa” (https://ec.europa.eu/taxation_customs/tin/) da
//Comissão Europeia pode auxiliar na verificação acerca da validade de um número de identificação fiscal informado.
//Alguns países não adotam um NIF para seus contribuintes. Outros não possuem um número único de identificação fiscal.
//Exemplos de situações:
//4) “Proprietário” possui CPF e não possui NIF:
//tpNI = 1 (CPF)
//NIProprietario = número do CPF
//NIF – Não informar
//5) “Proprietário” não possui CPF, mas possui NIF
//tpNI = 3
//NIProprietario = número do NIF
//NIF = Preencher o mesmo número do NIF informado no campo NIProprietario e o seu país de emissão, nos campos 31 e 32,
//respectivamente.
//6) “Proprietário” possui CPF e possui três NIFs:
//tpNI = 1 (CPF)
//NIProprietario = número do CPF
//NIF 1 = Preencher o número do NIF 1 e seu respectivo país de emissão nos campos 31 e 32
//NIF 2 = Informar outro grupo NIF (campo 30) com número do NIF 2 e seu respectivo país de emissão nos campos 31 e 32
//NIF 3 = Informar outro grupo NIF (campo 30) com número do NIF 3 e seu respectivo país de emissão nos campos 31 e 32
$nIIF = [
    ['numero'=>'', 'pais'=>'']
];

//Preencher com o nome do “proprietário” (conforme conceito descrito no campo 27 deste Evento), sendo vedado o uso de
//caracteres especiais.
$nome = '';

//Preencher com o a data de nascimento do “proprietário” (conforme conceito descrito no campo 27 deste Evento), quando
//disponível, no formato AAAA-MM-DD.
$dataNasc = '';

//Neste campo deve ser preenchido o endereço do “proprietário” (conforme conceito descrito no campo 27 deste Evento), em
//formato livre, sendo vedado o uso de caracteres especiais. O endereço a ser preenchido é o endereço para correspondência
//cadastrado junto à entidade declarante. Na hipótese de múltiplos endereços, utilizar o endereço principal de relacionamento com a
//entidade declarante.
$endereco = '';

//Este grupo contém a informação do país do endereço do “proprietário” (conforme conceito descrito no campo 27 deste
//Evento).
$pais = '';

//Preencher com o código do país de residência fiscal do “proprietário” (conforme conceito descrito no campo 27 deste Evento),
//de acordo com a Tabela de Países vigente na data de recepção do evento.
$paisResid = '';

//Preencher com o código do país de nacionalidade do “proprietário” (conforme conceito descrito no campo 27 deste Evento),
//de acordo com a Tabela de Países vigente na data de recepção do evento.
$paisNacionalidade = '';

//Este grupo contém a informação dos países para os quais o “proprietário” (conforme conceito descrito no campo 27 deste
//Evento) deve ser reportado.
//Após realizar a diligência devida, a entidade declarante deve ser capaz de determinar se os dados referentes ao “proprietário”
//em questão devem ser reportados apenas para a administração tributária local (preencher o campo 43 com “BR”) ou se também
//devem ser reportados para outras jurisdições, por força de algum acordo de troca automática de informações, como é o caso do
//FATCA.
//O grupo deverá ser repetido quantas vezes forem necessárias, de modo a contemplar todos os países para os quais o
//“proprietário” deve ser reportado, caso sejam identificados múltiplos países destinatários da informação.
//Atentar para o fato de que cada “proprietário” tem seu respectivo grupo de países a ser reportado. Assim, a análise acerca do
//reporte dos “proprietários” para administrações tributárias estrangeiras deve ser feita individualmente para cada “proprietário”,
//utilizando os critérios de diligência estabelecidos no respectivo acordo de troca de informações, conforme o caso.
//Um “proprietário” só pode ser reportável a um país se tiver sido informada ao menos uma “conta” (conforme conceito definido
//no campo 47) reportável ao mesmo país, no Evento em questão (campo 57).
//Exemplos:
//1) Entidade passiva com dois “proprietários” identificados, sendo o primeiro reportável apenas para o Brasil e o segundo
//reportável aos EUA:
//Proprietário 1 – Grupo 42 informado apenas uma vez, com o código “BR” no campo 43;
//Proprietário 2 – Grupo 42 informado duas vezes: uma com o código “BR” no campo 43, outra com o código “US” no campo 43
//2) Entidade passiva com três “proprietários” identificados, sendo o primeiro reportável apenas para o Brasil, o segundo
//reportável aos EUA e o terceiro reportável à Argentina (na situação hipotética de acordo vigente com este país):
//Proprietário 1 – Grupo 42 informado apenas uma vez, com o código “BR” no campo 43;
//Proprietário 2 – Grupo 42 informado duas vezes: uma com o código “BR” no campo 43, outra com o código “US” no campo 43
//Proprietário 3 – Grupo 42 informado duas vezes: uma com o código “BR” no campo 43, outra com o código “AR” no campo 43
$reportavel = [];

//NOTA : vão existir quantos proprietários forem necessários 
//esse método irá gerar um conjunto de proprietários com 1 ou mais registros
$evt->proprietarios($tpNI, $nIProprietario, $nIIF, $nome, $dataNasc, $endereco, $pais, $paisResid, $paisNacionalidade, $reportavel);



//########################################################################
$evt->monta();

//########################################################################
$evt->assina();

//########################################################################
if (! $evt->valida()) {
    var_dump($evt->errors);
    exit();
}

//########################################################################
header('Content-type: text/xml; charset=UTF-8');
echo $evt->getXML();
