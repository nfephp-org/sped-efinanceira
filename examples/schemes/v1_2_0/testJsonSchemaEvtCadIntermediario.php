<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtCadIntermediario';
$version = '1_2_0';

$jsonSchema = '{
    "title": "evtCadIntermediario",
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
        "tpni": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 1,
            "maximum": 5
        },
        "niintermediario": {
            "required": false,
            "type": ["string","null"],
            "minLength": 3,
            "maxLength": 25
        },
        "nomeintermediario": {
            "required": true,
            "type": "string",
            "minLength": 3,
            "maxLength": 100
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
                "município": {
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
                "paisresidencia": {
                    "required": true,
                    "type": "string",
                    "minLength": 2,
                    "maxLength": 2
                }
            }
        }
    }
}';

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '12345asdfe';
$std->giin = '12ASDA.12345.LE.123';
$std->tpni = 1;
$std->niintermediario = '123454ssls';
$std->nomeintermediario = 'kdkdkjdkdjkdj';

$std->endereco = new \stdClass();
$std->endereco->enderecolivre = 'sslskslslks';
$std->endereco->município = 'slkslsklsklsks';
$std->endereco->pais = 'BR';
$std->endereco->paisresidencia  = 'BR';


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
