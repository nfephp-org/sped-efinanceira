# SPED-EFINANCEIRA (versão 2.0 - layout 1.2.4) atualização 23/08/2021

**API PHP para a integração de aplicativo com o projeto SPED eFinanceira da Receita Federal do Brasil**

> NOTA: quando a receita fornece arquivos PEM ao inves de CER use, lembrando de deixar apenas o certificado e removendo o restante dos textos que existirem.
```
openssl x509 -outform der -in pre_web.pem -out pre_web.cer
openssl x509 -inform der -in prepro-efinanc_web.cer -out preprod-efinanc_web.pem

```

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


## Install


## Change log

Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testes

``` bash
$ composer test
```

## Segurança

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Creditos

- Roberto L. Machado <linux.rlm@gmail.com>

O desenvolvimento desse pacote somente foi possivel devido a contribuição e colaboração da
[ACTUARY Ltda](http://www.actuary.com.br/v3/)

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
