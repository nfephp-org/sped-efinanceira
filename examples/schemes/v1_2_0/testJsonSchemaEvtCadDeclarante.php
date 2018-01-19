<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtCadDeclarante';
$version = '1_2_0';

$jsonSchema = '{
    "title": "evtCadDeclarante",
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
        "infocadastro": {
            "required": true,
            "type": "object",
            "properties": {
                "giin": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF|SD|SS|SB|SP)[.][0-9]{3})$"
                },
                "categoriadeclarante": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "FATCA601|FATCA602|FATCA603|FATCA604|FATCA605|FATCA606|FATCA610|FATCA611"
                },
                "nome": {
                    "required": true,
                    "type": "string",
                    "minLength": 3,
                    "maxLength": 100
                },
                "tpnome": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "OECD202|OECD203|OECD204|OECD205|OECD206|OECD207|OECD208"
                },
                "enderecolivre": {
                    "required": true,
                    "type": "string",
                    "minLength": 3,
                    "maxLength": 200
                },
                "tpendereco": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "OECD301|OECD302|OECD303|OECD304|OECD305"
                },
                "municipio": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[0-9]{7}"
                },
                "uf": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[A-Z]{2}"
                },
                "cep": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[0-9]{8}"
                },
                "pais": {
                    "required": true,
                    "type": "string",
                    "pattern": "^[A-Z]{2}"
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
                                "pattern": "^[A-Z]{2}"
                            },
                            "tpnif": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 3,
                                "maxLength": 30
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
                                "pattern": "^[A-Z]{2}"
                            }
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
                                "pattern": "OECD301|OECD302|OECD303|OECD304|OECD305"
                            },
                            "enderecolivre": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 3,
                                "maxLength": 200
                            },
                            "pais": {
                                "required": true,
                                "type": "string",
                                "pattern": "^[A-Z]{2}"
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
                                        "pattern": "^[A-Z]{2}"
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
                                                "minLength": 3,
                                                "maxLength": 10
                                            },
                                            "complemento": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 10
                                            },
                                            "andar": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 10
                                            },
                                            "bairro": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 40
                                            },
                                            "caixapostal": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 12
                                            }
                                        }
                                    }
                                }
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
