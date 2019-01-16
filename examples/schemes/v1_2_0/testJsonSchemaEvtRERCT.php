<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtRERCT';
$version = '1_2_0';

$jsonSchema = '{
    "title": "evtRERCT",
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
        "ideeventorerct": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 2
        },
        "idedeclarado": {
            "required": true,
            "type": "object",
            "properties": {
                "tpinscr": {
                    "required": true,
                    "type": "integer",
                    "minimum": 1,
                    "maximum": 2
                },
                "nrinscr": {
                    "required": true,
                    "type": "string",
                    "pattern": "[0-9]{11,14}"
                }
            }
        },
        "rerct": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "nomebancoorigem": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 3,
                        "maxLength": 100
                    },
                    "paisorigem": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 2,
                        "maxLength": 2
                    },
                    "bicbancoorigem": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 11,
                        "maxLength": 11
                    },
                    "infocontaexterior": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpcontaexterior": {
                                    "required": false,
                                    "type": ["integer","null"],
                                    "minimum": 1,
                                    "maximum": 3
                                },
                                "numcontaexterior": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 3,
                                    "maxLength": 50
                                },
                                "vlrultdia": {
                                    "required": false,
                                    "type": ["number","null"]
                                },
                                "moeda": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 3,
                                    "maxLength": 3
                                },
                                "titular": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "nometitular": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 100
                                            },
                                            "tpinscr": {
                                                "required": false,
                                                "type": ["integer","null"],
                                                "minimum": 1,
                                                "maximum": 2
                                            },
                                            "nrinscr": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "pattern": "[0-9]{11,14}"
                                            },
                                            "niftitular": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 25
                                            }
                                        }
                                    }    
                                },
                                "beneficiariofinal": {
                                    "required": false,
                                    "type": ["array","null"],
                                    "minItems": 0,
                                    "items": {
                                        "type": "object",
                                        "properties": {
                                            "nomebeneficiariofinal": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 3,
                                                "maxLength": 100
                                            },
                                            "cpfbeneficiariofinal": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "pattern": "[0-9]{11}"
                                            },
                                            "nifbeneficiariofinal": {
                                                "required": false,
                                                "type": ["string","null"],
                                                "minLength": 1,
                                                "maxLength": 25
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
$std->ideeventorerct = 1;

$std->idedeclarado = new \stdClass();
$std->idedeclarado->tpinscr = 1;
$std->idedeclarado->nrinscr = '12345678901234';

$std->rerct[0] = new \stdClass();
$std->rerct[0]->nomebancoorigem = 'alkslskaoiaoiaoia';
$std->rerct[0]->paisorigem = 'BR';
$std->rerct[0]->bicbancoorigem = 'ODLKRTDNSRQ';

$std->rerct[0]->infocontaexterior[0] = new \stdClass();
$std->rerct[0]->infocontaexterior[0]->tpcontaexterior = 1;
$std->rerct[0]->infocontaexterior[0]->numcontaexterior = 'slsl54544';
$std->rerct[0]->infocontaexterior[0]->vlrultdia = 2500.23;
$std->rerct[0]->infocontaexterior[0]->moeda = 'ABC';

$std->rerct[0]->infocontaexterior[0]->titular[0] = new \stdClass();
$std->rerct[0]->infocontaexterior[0]->titular[0]->nometitular = 'ksjksjksjksjksj';
$std->rerct[0]->infocontaexterior[0]->titular[0]->tpinscr = 2;
$std->rerct[0]->infocontaexterior[0]->titular[0]->nrinscr = '12345678901';
$std->rerct[0]->infocontaexterior[0]->titular[0]->niftitular = '2929292929292';

$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0] = new \stdClass();
$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0]->nomebeneficiariofinal = 'lkjsljslksjksj';
$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0]->cpfbeneficiariofinal = '12345678901';
$std->rerct[0]->infocontaexterior[0]->beneficiariofinal[0]->nifbeneficiariofinal = '54545454545454';


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
