<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtMovPP';
$version = '1_2_1';

$jsonSchema = '{
    "title": "evtMovOpFin",
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
        "tpni": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 99
        },
        "nideclarado": {
            "required": true,
            "type": "string",
            "minLength": 1,
            "maxLength": 25
        },
        "nomedeclarado": {
            "required": true,
            "type": "string",
            "minLength": 1,
            "maxLength": 100
        },
        "anomescaixa": {
            "required": true,
            "type": "string",
            "pattern": "^(20([0-9][0-9])(0[1-9]|1[0-3]))$"
        },
        "infoprevpriv": {
            "required": true,
            "type": "array",
            "minItems": 1,
            "items": {
                "type": "object",
                "properties": {
                    "numproposta": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 10
                    },
                    "numprocesso": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 20
                    },
                    "produto": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "tpproduto": {
                                "required": true,
                                "type": "string",
                                "pattern": "^[0-9]{2}$"
                            },
                            "opcaotributacao": {
                                "required": true,
                                "type": "integer",
                                "minumum": 1,
                                "maximum": 2
                            }
                        }
                    },
                    "tpplano": {
                        "required": false,
                        "type": ["string","null"],
                        "pattern": "^[0-9]{2}$"
                    },
                    "vlrprincipal": {
                        "required": true,
                        "type": "number"
                    },
                    "vlrrendimentos": {
                        "required": true,
                        "type": "number"
                    },
                    "aplic": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "vlrcontribuicao": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrcarregamento": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrpartpf": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrpartpj": {
                                    "required": true,
                                    "type": "number"
                                },
                                "cnpj": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{14}$"
                                }
                            }
                        }    
                    },
                    "resg": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "vlraliquotairrf": {
                                    "required": true,
                                    "type": "number"
                                },
                                "numanoscarencia": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrresgateprincipal": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrresgaterendimentos": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrirrf": {
                                    "required": true,
                                    "type": "number"
                                }
                            }
                        }    
                    },
                    "benef": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpni": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 99
                                },
                                "niparticipante": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 11
                                },
                                "codreceita": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{4}$"
                                },
                                "prazovigencia": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 0,
                                    "maximum": 999
                                },
                                "vlrmensalinicial": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrbruto": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrliquido": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlrirrf": {
                                    "required": true,
                                    "type": "number"
                                },
                                "vlraliquotairrf": {
                                    "required": true,
                                    "type": "number"
                                },
                                "competenciapgto": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(0?[1-9]{1}|1[0-3]{1})$"
                                }
                            }
                        }
                    },
                    "saldofinal": {
                        "type": "object",
                        "properties": {
                            "vlrprincipal": {
                                "required": true,
                                "type": "number"
                            },
                            "vlrrendimentos": {
                                "required": true,
                                "type": "number"
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
$std->tpni = 2;
$std->tpdeclarado = 'klsks';
$std->nideclarado = 'ssss';
$std->nomedeclarado = 'slkcskkslsklsklsk';
$std->anomescaixa = '201712';

$std->infoprevpriv[0] = new \stdClass();
$std->infoprevpriv[0]->numproposta = '12';
$std->infoprevpriv[0]->numprocesso = '22222';

$std->infoprevpriv[0]->produto = new \stdClass();
$std->infoprevpriv[0]->produto->tpproduto = '01';
$std->infoprevpriv[0]->produto->opcaotributacao = 1;

$std->infoprevpriv[0]->tpplano = '02';
$std->infoprevpriv[0]->vlrprincipal = 10111.11;
$std->infoprevpriv[0]->vlrrendimentos = 1111.11;

$std->infoprevpriv[0]->aplic[0] = new \stdClass();
$std->infoprevpriv[0]->aplic[0]->vlrcontribuicao = 1111.11;
$std->infoprevpriv[0]->aplic[0]->vlrcarregamento = 10000.00;
$std->infoprevpriv[0]->aplic[0]->vlrpartpf = 5000.00;
$std->infoprevpriv[0]->aplic[0]->vlrpartpj = 6000.00;
$std->infoprevpriv[0]->aplic[0]->cnpj = '12345678901234';

$std->infoprevpriv[0]->resg[0] = new \stdClass();
$std->infoprevpriv[0]->resg[0]->vlraliquotairrf = 10.11;
$std->infoprevpriv[0]->resg[0]->numanoscarencia = 8.15;
$std->infoprevpriv[0]->resg[0]->vlrresgateprincipal = 11111.11;
$std->infoprevpriv[0]->resg[0]->vlrresgaterendimentos = 1.11;
$std->infoprevpriv[0]->resg[0]->vlrirrf = 14.54;

$std->infoprevpriv[0]->benef[0] = new \stdClass();
$std->infoprevpriv[0]->benef[0]->tpni = 2;
$std->infoprevpriv[0]->benef[0]->niparticipante = '45343434';
$std->infoprevpriv[0]->benef[0]->codreceita = '3277';
$std->infoprevpriv[0]->benef[0]->prazovigencia = 874;
$std->infoprevpriv[0]->benef[0]->vlrmensalinicial = 2451.56;
$std->infoprevpriv[0]->benef[0]->vlrbruto = 2875.54;
$std->infoprevpriv[0]->benef[0]->vlrliquido = 1865.22;
$std->infoprevpriv[0]->benef[0]->vlrirrf = 110.11;
$std->infoprevpriv[0]->benef[0]->vlraliquotairrf = 12.01;
$std->infoprevpriv[0]->benef[0]->competenciapgto = '11';

$std->infoprevpriv[0]->saldofinal= new \stdClass();
$std->infoprevpriv[0]->saldofinal->vlrprincipal = 11457.59;
$std->infoprevpriv[0]->saldofinal->vlrrendimentos = 2598.89;


// Schema must be decoded before it can be used for validation
$jsonSchemaObject = json_decode($jsonSchema);
if (empty($jsonSchemaObject)) {
    echo "<h2>Erro de digitacão no schema ! Revise</h2>";
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
