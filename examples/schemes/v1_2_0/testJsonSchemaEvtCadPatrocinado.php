<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtCadPatrocinado';
$version = '1_2_0';

$jsonSchema = '{
    "title": "evtCadPatrocinado",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{1,18}"
        },
        "indretificacao": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 3
        },
        "nrrecibo": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^([0-9]{1,18}[-][0-9]{2}[-][0-9]{3}[-][0-9]{4}[-][0-9]{1,18})$"
        },
        "giin": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF|SD|SS|SB|SP)[.][0-9]{3})$"
        },
        "categoriapatrocinador": {
            "required": false,
            "type": ["string","null"],
            "pattern": "FATCA601|FATCA602|FATCA603|FATCA604|FATCA605|FATCA606|FATCA610|FATCA611"
        },
        "infopatrocinado": {
            "required": true,
            "type": "object",
            "properties": {
                "giin": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF|SD|SS|SB|SP)[.][0-9]{3})$"
                },
                "cnpj": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[0-9]{14}"
                },
                "nif": {
                    "required": false,
                    "type": ["array","null"],
                    "minItems": 0,
                    "items": {
                        "type": "object",
                        "properties": {
                            "numeronif": {
                                "required": true,
                                "type": "string",
                                "minLength": 3,
                                "maxLength": 25
                            },
                            "paisemissao": {
                                "required": true,
                                "type": "string",
                                "minLength": 2,
                                "maxLength": 2
                            },
                            "tpnif": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 30
                            },
                            "nomepatrocinado": {
                                "required": true,
                                "type": "string",
                                "minLength": 3,
                                "maxLength": 100
                            },
                            "tpnome": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 7
                            }
                        }
                    }    
                },
                "endereco": {
                    "required": true,
                    "type": "object",
                    "properties": {
                        "enderecolivre": {
                            "required": true,
                            "type": "string",
                            "minLength": 3,
                            "maxLength": 200
                        },
                        "cep": {
                            "required": true,
                            "type": "string",
                            "pattern": "^[0-9]{8}"
                        },
                        "municipio": {
                            "required": true,
                            "type": "string",
                            "minLength": 3,
                            "maxLength": 100
                        },
                        "pais": {
                            "required": true,
                            "type": "string",
                            "minLength": 2,
                            "maxLength": 2
                        },
                        "tpendereco": {
                            "required": false,
                            "type": ["string","null"],
                            "minLength": 1,
                            "maxLength": 7
                        }
                    }
                },
                "enderecooutros": {
                    "required": false,
                    "type": ["array","null"],
                    "minItems": 0,
                    "items": {
                        "type": "object",
                        "properties": {
                            "tpendereco": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 7
                            },
                            "enderecolivre": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 3,
                                "maxLength": 200
                            },
                            "enderecoestrutura": {
                                "required": false,
                                "type": ["object","null"],
                                "properties": {
                                    "enderecolivre": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 3,
                                        "maxLength": 200
                                    },
                                    "endereco": {
                                        "required": false,
                                        "type": ["object","null"],
                                        "properties": {
                                            "logradouro": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 60
                                            },
                                            "numero": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 10                                            
                                            },
                                            "complemento": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 10                                            
                                            },
                                            "andar": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 10
                                            },
                                            "bairro": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 40
                                            },
                                            "caixapostal": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 12
                                            },
                                            "cep": {
                                                "required": true,
                                                "type": "string",
                                                "pattern": "^[0-9]{8}"
                                            },
                                            "municipio": {
                                                "required": true,
                                                "type": "string",
                                                "minLength": 3,
                                                "maxLength": 60
                                            },
                                            "uf": {
                                                "required": true,
                                                "type": "string",
                                                "minLength": 2,
                                                "maxLength": 40
                                            },
                                            "pais": {
                                                "required": true,
                                                "type": "string",
                                                "minLength": 2,
                                                "maxLength": 2
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } 
                },
                "paisresid": {
                    "required": true,
                    "type": "array",
                    "minItems": 1,
                    "items": {
                        "type": "object",
                        "properties": {
                            "pais": {
                                "required": true,
                                "type": "string",
                                "minLength": 2,
                                "maxLength": 2
                            }
                        }
                    }    
                }
            }
        }
    }
}';

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->giin = '12ASDA.12345.LE.123';
$std->categoriapatrocinador = 'FATCA601';

$std->infopatrocinado = new \stdClass();
$std->infopatrocinado->giin = '12ASDA.12345.LE.123';
$std->infopatrocinado->cnpj = '12345678901234';

$std->infopatrocinado->nif[0] = new \stdClass();
$std->infopatrocinado->nif[0]->numeronif = 'sa123';
$std->infopatrocinado->nif[0]->paisemissao = 'BR';
$std->infopatrocinado->nif[0]->tpnif = '1111';
$std->infopatrocinado->nif[0]->nomepatrocinado = 'sjlskjslkjskj';
$std->infopatrocinado->nif[0]->tpnome = 'lks1';

$std->infopatrocinado->endereco = new \stdClass();
$std->infopatrocinado->endereco->enderecolivre = 'jlkjksjlskj';
$std->infopatrocinado->endereco->cep = '12345678';
$std->infopatrocinado->endereco->municipio = 'kslksçlks';
$std->infopatrocinado->endereco->pais = 'BR';
$std->infopatrocinado->endereco->tpendereco = '1234asd';

$std->infopatrocinado->enderecooutros[0] = new \stdClass();
$std->infopatrocinado->enderecooutros[0]->tpendereco = '1234asd';
$std->infopatrocinado->enderecooutros[0]->enderecolivre = 'kjslksjksj';

$std->infopatrocinado->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->enderecolivre = 'kjskj';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'çlksçksçlks';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->numero = 'jhjh11';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'kwjk';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->andar = '1234';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'skjhsh';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = '111sd';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->cep = '12345678';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->municipio = 'skjskjsjks';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->uf = 'Acre';
$std->infopatrocinado->enderecooutros[0]->enderecoestrutura->endereco->pais = 'BR';

$std->infopatrocinado->paisresid[0] = new \stdClass();
$std->infopatrocinado->paisresid[0]->pais = 'BR';

// Schema must be decoded before it can be used for validation
$jsonSchemaObject = json_decode($jsonSchema);
if (empty($jsonSchemaObject)) {
    echo "<h2>Erro de digitação no schema ! Revise</h2>";
    echo "<pre>";
    print_r($jsonSchema);
    echo "</pre>";
    die();
}
// The SchemaStorage can resolve references, loading additional schemas from file as needed, etc.
$schemaStorage = new SchemaStorage();

// This does two things:
// 1) Mutates $jsonSchemaObject to normalize the references (to file://mySchema#/definitions/integerData, etc)
// 2) Tells $schemaStorage that references to file://mySchema... should be resolved by looking in $jsonSchemaObject
$schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);

// Provide $schemaStorage to the Validator so that references can be resolved during validation
$jsonValidator = new Validator(new Factory($schemaStorage));

// Do validation (use isValid() and getErrors() to check the result)
$jsonValidator->validate(
    $std,
    $jsonSchemaObject
);

if ($jsonValidator->isValid()) {
    echo "The supplied JSON validates against the schema.<br/>";
} else {
    echo "JSON does not validate. Violations:<br/>";
    foreach ($jsonValidator->getErrors() as $error) {
        echo sprintf("[%s] %s<br/>", $error['property'], $error['message']);
    }
    die;
}
//salva se sucesso
file_put_contents("../../../jsonSchemes/v$version/$evento.schema", $jsonSchema);
