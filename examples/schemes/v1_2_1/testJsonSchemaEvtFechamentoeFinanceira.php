<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtFechamentoeFinanceira';
$version = '1_2_1';

$jsonSchema = '{
    "title": "evtFechamentoeFinanceira",
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
        "dtinicio": {
            "required": true,
            "type": "string",
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
        },
        "dtfim": {
            "required": true,
            "type": "string",
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
        },
        "sitespecial": {
            "required": true,
            "type": "integer",
            "minimum": 0,
            "maximum": 5
        },
        "fechamentopp": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "fechamentomes": {
                    "required": true,
                    "type": "array",
                    "minItems": 1,
                    "items": {
                        "type": "object",
                        "properties": {
                            "anomescaixa": {
                                "required": true,
                                "type": "string",
                                "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])(0?[1-9]|1[0-3])$"
                            },
                            "quantarqtrans": {
                                "required": true,
                                "type": "integer",
                                "minimum": 1,
                                "maximum": 999999999
                            }
                        }
                    }
                }
            }    
        },
        "fechamentomovopfin": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "fechamentomes": {
                    "required": true,
                    "type": "array",
                    "minItems": 1,
                    "items": {
                        "type": "object",
                        "properties": {
                            "anomescaixa": {
                                "required": true,
                                "type": "string",
                                "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])(0?[1-9]|1[0-3])$"
                            },
                            "quantarqtrans": {
                                "required": true,
                                "type": "integer",
                                "minimum": 1,
                                "maximum": 999999999
                            }
                        }
                    }
                },
                "entdecexterior": {
                    "required": false,
                    "type": ["object","null"],
                    "properties": {
                        "contasareportar": {
                            "required": true,
                            "type": "integer",
                            "minimum": 0,
                            "maximum": 1
                        }
                    }
                },
                "entpatdecexterior": {
                    "required": false,
                    "type": ["array","null"],
                    "minItems": 0,
                    "items": {
                        "type": "object",
                        "properties": {
                            "giin": {
                                "required": true,
                                "type": "string",
                                "pattern": "^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF|SD|SS|SB|SP)[.][0-9]{3})$"
                            },
                            "cnpj": {
                                "required": true,
                                "type": "string",
                                "pattern": "^[0-9]{14}"
                            },
                            "contasareportar": {
                                "required": true,
                                "type": "integer",
                                "minimum": 0,
                                "maximum": 1
                            }
                        }
                    }    
                }
            }
        },
        "fechamentomovopfinanual": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "fechamentoano": {
                    "required": true,
                    "type": "object",
                    "properties": {
                        "anocaixa": {
                            "required": true,
                            "type": "string",
                            "pattern": "^20([0-9][0-9])"
                        },
                        "quantarqtrans": {
                            "required": true,
                            "type": "integer",
                            "minimum": 1,
                            "maximum": 999999999
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
$std->dtinicio = '2017-01-01';
$std->dtfim = '2017-05-31';
$std->sitespecial = 1;

$std->fechamentopp = new \stdClass();
$std->fechamentopp->fechamentomes[0] = new \stdClass();
$std->fechamentopp->fechamentomes[0]->anomescaixa = '201713';
$std->fechamentopp->fechamentomes[0]->quantarqtrans = 99999;

$std->fechamentomovopfin = new \stdClass();
$std->fechamentomovopfin->fechamentomes[0] = new \stdClass();
$std->fechamentomovopfin->fechamentomes[0]->anomescaixa = '201713';
$std->fechamentomovopfin->fechamentomes[0]->quantarqtrans = 99999;

$std->fechamentomovopfin->entdecexterior = new \stdClass();
$std->fechamentomovopfin->entdecexterior->contasareportar = 0;

$std->fechamentomovopfin->entpatdecexterior[0] = new \stdClass();
$std->fechamentomovopfin->entpatdecexterior[0]->giin = '12ASDA.12345.LE.123';
$std->fechamentomovopfin->entpatdecexterior[0]->cnpj = '12345678901234';
$std->fechamentomovopfin->entpatdecexterior[0]->contasareportar = 0;

$std->fechamentomovopfinanual = new \stdClass();
$std->fechamentomovopfinanual->fechamentoano = new \stdClass();
$std->fechamentomovopfinanual->fechamentoano->anocaixa = '2017';
$std->fechamentomovopfinanual->fechamentoano->quantarqtrans = 99999;

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
