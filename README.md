# SPED-EFINANCEIRA

**API PHP para a integração de aplicativo com o projeto SPED eFinanceira da Receita Federal do Brasil**

*sped-efinanceira* é um framework que permite a integração de um aplicativo, com o serviço do projeto SPED da RFB denominado *eFinanceira*, com a construção dos eventos em xml e do envio dos lotes de eventos e consultas, através de requisições SOAP, sobre SSL usando certificado digital modelo A1 (PKCS#12), pertencentes a cadeia de certificação Brasileira.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Por meio da Instrução Normativa número 1.571/2015, de 03 de julho, a Receita Federal instituiu uma nova obrigação acessória denominada e-Financeira. Com ela, a partir de fevereiro de 2016, os contribuintes que têm movimentação financeira nos Estados Unidos (EUA) deverão transmitir essa informação ao governo, por meio do Sistema Público de Escrituração Digital (Sped) . O manual para preenchimento dos leiautes da e-Financeira já está disponível no site da Receita Federal.

A nova obrigação acessória vale para pessoas jurídicas autorizadas a estruturar e comercializar planos de benefícios de previdência complementar; autorizadas a instituir e administrar Fundos de Aposentadoria Programada Individual (Fapi); que tenham como atividade principal ou acessória a captação, intermediação ou aplicação de recursos financeiros, próprios ou de terceiros, incluídas as operações de consórcio, em moeda nacional ou estrangeira, ou a custódia de valor de propriedade de terceiros; e as sociedades seguradoras autorizadas a estruturar e comercializar planos de seguros de pessoas. Ou seja, entre os responsáveis por prestar tais informações, destacam-se os bancos, seguradoras, corretoras de valores, distribuidores de títulos e valores mobiliários, administradores de consórcios e as entidades de previdência complementar.

Este pacote visa fornecer os meios para gerar, assinar e anviar os dados relativos a mais essa obrigação fiscal.

Este pacote faz parte da API NFePHP e atende aos parâmetros das PSR2 e PSR4, bem como é desenvolvida para de adequar as versões ATIVAS do PHP.

## Contribuindo

Este é um projeto totalmente *OpenSource*, para usa-lo e modifica-lo você não paga absolutamente nada. Porém para continuarmos a mante-lo é necessário qua alguma contribuição seja feita, seja auxiliando na codificação, na documentação ou na realização de testes e identificação de falhas e BUGs.

Para contribuir, por favor, observe as condições em [CONTRIBUTING](CONTRIBUTING.md) e o [Código de Conduta](CONDUCT.md) para maiores detalhes.

Mas também, caso você ache que qualquer informação obtida aqui, lhe foi útil e que isso vale de algum dinheiro e está disposto a doar algo, sinta-se livre para enviar qualquer quantia através de :

<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=linux%2erlm%40gmail%2ecom&lc=BR&item_name=NFePHP%20OpenSource%20API&item_number=nfephp&currency_code=BRL&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest">
<img alt="Doar com Paypal" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif"/></a>
[![](https://stc.pagseguro.uol.com.br/public/img/botoes/doacoes/209x48-doar-assina.gif)](https://pag.ae/bkXPq4) 


## Install

Via Composer

``` bash
$ composer require nfephp-org/sped-efinanceira
```

## Usage

Para instanciar a classe construtoras dos eventos em XML.
### Cadastro de Declarante
```php
use NFePHP\eFinanc\Factory\CadDeclarante;
//instancia a classe e passa o arquivo de configuração
$evt = new CadDeclarante('../config/config.json');
```
### Cadastro de Intermediário
```php
use NFePHP\eFinanc\Factory\CadIntermediario;
//instancia a classe e passa o arquivo de configuração
$evt = new CadIntermediario('../config/config.json');
```
### Cadastro de Patrocinado
```php
use NFePHP\eFinanc\Factory\CadPatrocinado;
//instancia a classe e passa o arquivo de configuração
$evt = new CadPatrocinado('../config/config.json');
```
### Abertura
```php
use NFePHP\eFinanc\Factory\Abertura;
//instancia a classe e passa o arquivo de configuração
$evt = new Abertura('../config/config.json');
```
### Movimento do Declarado
```php
use NFePHP\eFinanc\Factory\MovDeclarado;
//instancia a classe e passa o arquivo de configuração
$evt = new MovDeclarado('../config/config.json');
```
### Movimento do Patrocinado
```php
use NFePHP\eFinanc\Factory\Movimento;
//instancia a classe e passa o arquivo de configuração
$evt = new Movimento('../config/config.json');
```
### Fechamento
```php
use NFePHP\eFinanc\Factory\Fechamento;
//instancia a classe e passa o arquivo de configuração
$evt = new Fechamento('../config/config.json');
```
### Exclusão (parcial)
```php
use NFePHP\eFinanc\Factory\Exclusao;
//instancia a classe e passa o arquivo de configuração
$evt = new Exclusao('../config/config.json');
```
### Exclusão Total
```php
use NFePHP\eFinanc\Factory\ExclusaoTotal;
//instancia a classe e passa o arquivo de configuração
$evt = new ExclusaoTotal('../config/config.json');
```

Para instanciar a classe Tools, que realiza a comunicação propriamente dita com os webservices da Receita Federal.
``` php

use NFePHP\eFinanc\Tools;

$tools = new Tools('../config/config.json');

```

## Change log

Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testes

``` bash
$ composer test
```

## Segurança

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Creditos

- Rodrigo Traleski <rodrigo@actuary.com.br>
- Ademilson Santana da Silva <ademilsonssilva1@gmail.com>
- Roberto L. Machado <linux.rlm@gmail.com>

O desenvolvimento desse pacote somente foi possivel devido a contribuição e colaboração da 
[ACTUARY Ltda](http://www.actuary.com.br/v2/informatica/index.php) 

## Licenças

Este patote está diponibilizado sob LGPLv3 ou MIT License (MIT). Leia  [Arquivo de Licença](LICENSE.md) para maiores informações.

[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-efinanceira.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-efinanceira/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-efinanceira.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-efinanceira.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-efinanceira.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/sped-efinanceira
[link-travis]: https://travis-ci.org/nfephp-org/sped-efinanceira
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-efinanceira/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-efinanceira
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-efinanceira
[link-author]: https://github.com/nfephp-org

