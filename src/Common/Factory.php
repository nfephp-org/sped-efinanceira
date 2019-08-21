<?php

namespace NFePHP\eFinanc\Common;

/**
 * Classe Factory, performs build events
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use DateTime;
use DOMDocument;
use DOMElement;
use JsonSchema\Validator as JsonValid;
use NFePHP\Common\Certificate;
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\Common\Signer;
use NFePHP\Common\Strings;
use NFePHP\Common\Validator;
use NFePHP\eFinanc\Exception\EventsException;
use NFePHP\eFinanc\Common\Layouts;
use stdClass;

abstract class Factory
{
    /**
     * @var DateTime
     */
    public $date;
    /**
     * @var int
     */
    public $tpAmb;
    /**
     * @var string
     */
    public $verAplic;
    /**
     * @var string
     */
    public $layout;
    /**
     * @var string
     */
    public $cnpjDeclarante;
    /**
     * @var string
     */
    public $layoutStr;
    /**
     * @var string
     */
    public $evtTag;
    /**
     * @var string
     */
    public $evtName;
    /**
     * @var string
     */
    public $evtAlias;
    /**
     * @var stdClass
     */
    public $std;
    /**
     * @var string
     */
    public $xmlns = 'http://www.eFinanceira.gov.br/schemas/';
    /**
     * @var \DOMElement
     */
    public $eFinanceira;
    /**
     * @var \DOMElement
     */
    public $node;
    /**
     * @var string
     */
    public $evtid;
    /**
     *
     * @var \NFePHP\Common\DOMImproved
     */
    public $dom;
    /**
     * @var string
     */
    public $xml;
    /**
     * @var Certificate|null
     */
    protected $certificate;
    /**
     * @var string
     */
    protected $jsonschema;
    /**
     * @var string
     */
    protected $schema;
    /**
     * @var array
     */
    protected $versions;
    /**
     * Constructor
     * @param string $config
     * @param stdClass $std
     * @param Certificate $certificate
     * @param stdClass $params
     * @param string $date OPTIONAL DONT USE
     */
    public function __construct(
        $config,
        stdClass $std,
        stdClass $params,
        Certificate $certificate = null,
        $date = ''
    ) {
        //set properties from config
        $stdConf = json_decode($config);
        $this->date = new DateTime();
        if (!empty($date)) {
            $this->date = new DateTime($date);
        }
        $this->tpAmb = $stdConf->tpAmb;
        $this->verAplic = $stdConf->verAplic;
        $this->layout = $stdConf->eventoVersion;
        $lay = new Layouts($config);
        $this->versions = $lay->versions;
        $this->cnpjDeclarante = $stdConf->cnpjDeclarante;
        $this->layoutStr = $this->strLayoutVer($stdConf->eventoVersion);
        $this->certificate = $certificate;
        $this->evtTag = $params->evtTag;
        $this->evtName = $params->evtName;
        $this->evtAlias = $params->evtAlias;
        $this->layout = $this->versions[$this->evtName];
        if (empty($std) || !is_object($std)) {
            throw EventsException::wrongArgument(1003, '');
        }
        $this->jsonschema = realpath(
            __DIR__
            . "/../../jsonSchemes/$this->layoutStr/"
            . $this->evtName
            . ".schema"
        );
        $this->schema = realpath(
            __DIR__
            . "/../../schemes/$this->layoutStr/"
            . $this->evtName
            . "-v" . $this->layout
            . ".xsd"
        );
        //convert all data fields to lower case
        $this->std = $this->propertiesToLower($std);
        //validate input data with schema
        $this->validInputData($this->std);
        //Adding forgotten or unnecessary fields.
        //This is done for standardization purposes.
        //Fields with no value will not be included by the builder.
        //$this->std = $this->standardizeProperties($this->std);
        $this->init();
    }

    /**
     * Stringfy layout number
     * @param string $layout
     * @return string
     */
    protected function strLayoutVer($layout)
    {
        return "v" . str_replace('.', '_', $layout);
    }

    /**
     * Change properties names of stdClass to lower case
     * @param stdClass $data
     * @return stdClass
     */
    protected static function propertiesToLower(stdClass $data)
    {
        $properties = get_object_vars($data);
        $clone = new stdClass();
        foreach ($properties as $key => $value) {
            if ($value instanceof stdClass) {
                $value = self::propertiesToLower($value);
            }
            $nk = strtolower($key);
            $clone->{$nk} = $value;
        }
        return $clone;
    }

    /**
     * Validation json data from json Schema
     * @param stdClass $data
     * @return boolean
     * @throws \RuntimeException
     */
    protected function validInputData($data)
    {
        if (!is_file($this->jsonschema)) {
            return true;
        }
        $validator = new JsonValid();
        $validator->check($data, (object)['$ref' => 'file://' . $this->jsonschema]);
        if (!$validator->isValid()) {
            $msg = "";
            foreach ($validator->getErrors() as $error) {
                $msg .= sprintf("[%s] %s\n", $error['property'], $error['message']);
            }
            throw EventsException::wrongArgument(1004, $msg);
        }
        return true;
    }

    /**
     * Initialize DOM
     */
    protected function init()
    {
        if (empty($this->dom)) {
            $this->dom = new Dom('1.0', 'UTF-8');
            $this->dom->preserveWhiteSpace = false;
            $this->dom->formatOutput = false;
            $ns = $this->xmlns . $this->evtName . "/v" . $this->layout;
            $this->eFinanceira = $this->dom->createElementNS($ns, 'eFinanceira');
            //cria o node principal
            $this->evtid = FactoryId::build($this->std->sequencial);
            $this->node = $this->dom->createElement($this->evtTag);
            $att = $this->dom->createAttribute('id');
            $att->value = $this->evtid;
            $this->node->appendChild($att);
            $ideDeclarante = $this->dom->createElement("ideDeclarante");
            $this->dom->addChild(
                $ideDeclarante,
                "cnpjDeclarante",
                $this->cnpjDeclarante,
                true
            );
            $this->node->appendChild($ideDeclarante);
        }
    }
    
    /**
     * Returns alias of event
     * @return string
     */
    public function alias()
    {
        return $this->evtAlias;
    }
    
    /**
     * Returns the Certificate::class
     * @return Certificate|null
     */
    public function getCertificate()
    {
        return $this->certificate;
    }
    
    /**
     * Insert Certificate::class
     * @param Certificate $certificate
     */
    public function setCertificate(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }
    
    /**
     * Recover calculate ID
     * @return string
     */
    public function getId()
    {
        return $this->evtid;
    }

    /**
     * Return xml of event
     * @return string
     */
    public function toXML()
    {
        if (empty($this->xml)) {
            $this->toNode();
        }
        return $this->clearXml($this->xml);
    }

    abstract protected function toNode();

    /**
     * Remove XML declaration from XML string
     * @param string $xml
     * @return string
     */
    protected function clearXml($xml)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml);
        return $dom->saveXML($dom->documentElement);
    }

    /**
     * Convert xml of event to array
     * @return array
     */
    public function toArray()
    {
        return json_decode($this->toJson(), true);
    }

    /**
     * Convert xml to json string
     * @return string
     */
    public function toJson()
    {
        if (empty($this->xml)) {
            $this->toNode();
        }
        //signature only makes sense in XML, other formats should not contain
        //signature data
        $xml = Signer::removeSignature($this->xml);
        $dom = new \DOMDocument();
        $dom->loadXML($xml);
        $sxml = simplexml_load_string($dom->saveXML());
        return str_replace(
            '@attributes',
            'attributes',
            json_encode($sxml, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Convert xml to stdClass
     * @return stdClass
     */
    public function toStd()
    {
        return json_decode($this->toJson());
    }

    /**
     * Sign and validate XML with XSD, can throw Exception
     * @param string $tagsigned tag to be base of signature
     */
    protected function sign($tagsigned = '')
    {
        $xml = $this->dom->saveXML($this->eFinanceira);
        $xml = Strings::clearXmlString($xml);
        if (!empty($this->certificate)) {
            $xml = Signer::sign(
                $this->certificate,
                $xml,
                $tagsigned,
                'id',
                OPENSSL_ALGO_SHA256,
                [true, false, null, null]
            );
            //validation by XSD schema throw Exception if dont pass
            if ($this->schema) {
                Validator::isValid($xml, $this->schema);
            }
        }
        $this->xml = $xml;
    }
}
