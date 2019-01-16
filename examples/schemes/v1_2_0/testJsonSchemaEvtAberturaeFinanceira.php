<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtAberturaeFinanceira';
$version = '1_2_0';

$jsonSchema = '{
    "title": "evtAberturaeFinanceira",
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
        "aberturapp": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "tpempresa": {
                    "required": true,
                    "type": "array",
                    "minItems": 1,
                    "items": {
                        "type": "object",
                        "properties": {
                            "tpprevpriv": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 1
                            }
                        }
                    }    
                }    
            }
        },
        "aberturamovopfin": {
            "required": false,
            "type": ["object","null"],
            "properties": {
                "responsavelrmf": {
                    "required": true,
                    "type": "object",
                    "properties": {
                        "cpf": {
                            "required": true,
                            "type": "string",
                            "pattern": "^[0-9]{11}"
                        },
                        "nome": {
                            "required": true,
                            "type": "string",
                            "minLength": 3,
                            "maxLength": 100
                        },
                        "setor": {
                            "required": true,
                            "type": "string",
                            "minLength": 3,
                            "maxLength": 90
                        },
                        "telefone": {
                            "required": true,
                            "type": "object",
                            "properties": {
                                "ddd": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{2,3}"
                                },
                                "numero": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{4,10}"
                                },
                                "ramal": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^[0-9]{1,4}"
                                }
                            }    
                        },
                        "endereco": {
                            "required": true,
                            "type": "object",
                            "properties": {
                                "logradouro": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 3,
                                    "maxLength": 100
                                },
                                "numero": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 20                                
                                },
                                "complemento": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 1,
                                    "maxLength": 20
                                },
                                "bairro": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 3,
                                    "maxLength": 100
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
                                "uf": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 2,
                                    "maxLength": 2
                                }
                            }
                        }    
                    }
                },
                "respefin": {
                    "required": true,
                    "type": "array",
                    "minItems": 1,
                    "items": {
                        "type": "object",
                        "properties": {
                            "cpf": {
                                "required": true,
                                "type": "string",
                                "pattern": "^[0-9]{11}"
                            },
                            "nome": {
                                "required": true,
                                "type": "string",
                                "minLength": 3,
                                "maxLength": 100
                            },
                            "setor": {
                                "required": true,
                                "type": "string",
                                "minLength": 3,
                                "maxLength": 90
                            },
                            "email": {
                                "required": true,
                                "type": "email"
                            },
                            "telefone": {
                                "required": true,
                                "type": "object",
                                "properties": {
                                    "ddd": {
                                        "required": true,
                                        "type": "string",
                                        "pattern": "^[0-9]{2,3}"
                                    },
                                    "numero": {
                                        "required": true,
                                        "type": "string",
                                        "pattern": "^[0-9]{4,10}"
                                    },
                                    "ramal": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "pattern": "^[0-9]{1,4}"
                                    }
                                }    
                            },
                            "endereco": {
                                "required": true,
                                "type": "object",
                                "properties": {
                                    "logradouro": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 3,
                                        "maxLength": 100
                                    },
                                    "numero": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 1,
                                        "maxLength": 20                                
                                    },
                                    "complemento": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 20
                                    },
                                    "bairro": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 3,
                                        "maxLength": 100
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
                                    "uf": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 2,
                                        "maxLength": 2
                                    }
                                }
                            }
                        }
                    }    
                },
                "represlegal": {
                    "required": true,
                    "type": "object",
                    "properties": {
                        "cpf": {
                            "required": true,
                            "type": "string",
                            "pattern": "^[0-9]{11}"
                        },
                        "setor": {
                            "required": true,
                            "type": "string",
                            "minLength": 3,
                            "maxLength": 90
                        },
                        "telefone": {
                            "required": true,
                            "type": "object",
                            "properties": {
                                "ddd": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{2,3}"
                                },
                                "numero": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{4,10}"
                                },
                                "ramal": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^[0-9]{1,4}"
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
$std->dtinicio = '2017-01-01'; //A data informada deve pertencer ao mesmo semestre da dtFim
$std->dtfim = '2017-05-31';

$std->aberturapp = new \stdClass();
$std->aberturapp->tpempresa[0] = new \stdClass();
$std->aberturapp->tpempresa[0]->tpprevpriv = 'X';

$std->aberturamovopfin = new \stdClass();
$std->aberturamovopfin->responsavelrmf = new \stdClass();
$std->aberturamovopfin->responsavelrmf->cpf = '12345678901';
$std->aberturamovopfin->responsavelrmf->nome = 'lkslsklsklskslksl';
$std->aberturamovopfin->responsavelrmf->setor = 'lkslsklsk';
$std->aberturamovopfin->responsavelrmf->telefone = new \stdClass();
$std->aberturamovopfin->responsavelrmf->telefone->ddd = '11';
$std->aberturamovopfin->responsavelrmf->telefone->numero = '5555555';
$std->aberturamovopfin->responsavelrmf->telefone->ramal = '123';

$std->aberturamovopfin->responsavelrmf->endereco = new \stdClass();
$std->aberturamovopfin->responsavelrmf->endereco->logradouro = 'jhskhjskjhsjshjh';
$std->aberturamovopfin->responsavelrmf->endereco->numero = 'km123';
$std->aberturamovopfin->responsavelrmf->endereco->complemento = 'lkwlkwlkw';
$std->aberturamovopfin->responsavelrmf->endereco->bairro = 'jdkjdkjd';
$std->aberturamovopfin->responsavelrmf->endereco->cep = '12345678';
$std->aberturamovopfin->responsavelrmf->endereco->municipio = 'lksklsk';
$std->aberturamovopfin->responsavelrmf->endereco->uf = 'AC';

$std->aberturamovopfin->respefin[1] = new \stdClass();
$std->aberturamovopfin->respefin[1]->cpf = '12345678901';
$std->aberturamovopfin->respefin[1]->nome = 'lkslsklsklskslksl';
$std->aberturamovopfin->respefin[1]->setor = 'lkslsklsk';
$std->aberturamovopfin->respefin[1]->email = 'ksksk@msmsl.com';
$std->aberturamovopfin->respefin[1]->telefone = new \stdClass();
$std->aberturamovopfin->respefin[1]->telefone->ddd = '11';
$std->aberturamovopfin->respefin[1]->telefone->numero = '5555555';
$std->aberturamovopfin->respefin[1]->telefone->ramal = '123';

$std->aberturamovopfin->respefin[1]->endereco = new \stdClass();
$std->aberturamovopfin->respefin[1]->endereco->logradouro = 'jhskhjskjhsjshjh';
$std->aberturamovopfin->respefin[1]->endereco->numero = 'km123';
$std->aberturamovopfin->respefin[1]->endereco->complemento = 'lkwlkwlkw';
$std->aberturamovopfin->respefin[1]->endereco->bairro = 'jdkjdkjd';
$std->aberturamovopfin->respefin[1]->endereco->cep = '12345678';
$std->aberturamovopfin->respefin[1]->endereco->municipio = 'lksklsk';
$std->aberturamovopfin->respefin[1]->endereco->uf = 'AC';


$std->aberturamovopfin->represlegal = new \stdClass();
$std->aberturamovopfin->represlegal->cpf = '12345678901';
$std->aberturamovopfin->represlegal->setor = 'lkslsklsk';
$std->aberturamovopfin->represlegal->telefone = new \stdClass();
$std->aberturamovopfin->represlegal->telefone->ddd = '11';
$std->aberturamovopfin->represlegal->telefone->numero = '5555555';
$std->aberturamovopfin->represlegal->telefone->ramal = '123';

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
