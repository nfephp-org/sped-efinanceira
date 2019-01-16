<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'consultarIntermediario';
$version = '1_2_0';

$jsonSchema = '{
    "title": "consultarIntermediario",
    "type": "object",
    "properties": {
        "cnpj": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{14}"
        },
        "ginn": {
            "required": false,
            "type": ["string","null"],
            "maxLength": "19",
            "pattern": "^[0-9]"
        },
        "tiponi": {
            "required": false,
            "type": ["integer","null"],
            "minimum": 0,
            "maximum": 4
        },
        "numeroidentificacao": {
            "required": false,
            "type": ["string","null"],
            "maxLength": "28"
        }
    }
}';

$std = new \stdClass();
$std->cnpj = '12345678901234';
$std->ginn = '1234567890123456789';
$std->tiponi = 1;
$std->numeroidentificacao = '12345678901';

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
