<?php

$filexsd = '../schemes/envioLoteEventos-v1_0_1.xsd';
$filename = 'AberturaLote-ASSINADO.xml';

//$filexsd = '../schemes/evtAberturaeFinanceira-v1_0_1.xsd';
//$filename = 'evtAberturaeFinanceira.xml';

$xsd = file_get_contents($filexsd);
$xml = file_get_contents($filename);
//header("Content-Type:text/xml");
//echo $xml;


libxml_use_internal_errors(true);
libxml_clear_errors();
$dom = new \DOMDocument('1.0', 'utf-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = false;
$dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
libxml_clear_errors();

if (! $dom->schemaValidate($xsd)) {
    $aIntErrors = libxml_get_errors();
    foreach ($aIntErrors as $intError) {
        echo $intError->message . "<br>";
    }
}
