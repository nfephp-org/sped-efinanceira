<?php

namespace NFePHP\eFinanc\Common;

/**
 * Class Common\Tools, basic structures
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use stdClass;
use NFePHP\Common\Certificate;
use NFePHP\eFinanc\Common\Soap\SoapCurl;
use NFePHP\eFinanc\Common\Soap\SoapInterface;
use NFePHP\eFinanc\Common\Layouts;
use DateTime;

class Tools
{
    /**
     * @var string
     */
    public $request;
    /**
     * @var string
     */
    public $response;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $der;
    /**
     * @var array
     */
    protected $soapnamespaces = [
        'xmlns:soapenv' => "http://schemas.xmlsoap.org/soap/envelope/",
        'xmlns:sped'=> "http://sped.fazenda.gov.br/"
    ];
    /**
     * @var Certificate
     */
    protected $certificate;
    /**
     * @var int
     */
    protected $tpAmb = 2;
    /**
     * @var string
     */
    protected $verAplic;
    /**
     * @var string
     */
    protected $eventoVersion;
    /**
     * @var string
     */
    protected $cnpjDeclarante;
    /**
     * @var SoapInterface
     */
    protected $soap;
    /**
     * @var \DateTime
     */
    protected $date;
    /**
     * @var array
     */
    protected $versions;
    /**
     * Constructor
     * @param string $config
     * @param Certificate $certificate
     */
    public function __construct(
        string $config,
        \NFePHP\Common\Certificate $certificate
    ) {
        //set properties from config
        $stdConf = json_decode($config);
        $this->tpAmb = $stdConf->tpAmb;
        $this->verAplic = $stdConf->verAplic;
        $this->eventoVersion = $stdConf->eventoVersion;
        $this->date = new \DateTime();
        $this->cnpjDeclarante = $stdConf->cnpjDeclarante;
        $this->certificate = $certificate;
        $this->path = realpath(
            __DIR__ . '/../../'
        ).'/';
        $lay = new Layouts($config);
        $this->versions = $lay->versions;
        //loads the encryption certificates distributed in the package
        $this->der = file_get_contents($this->path.'storage'.DIRECTORY_SEPARATOR.'preprod-efinanc_web.cer');
        if ($this->tpAmb == 1) {
            $this->der = file_get_contents($this->path.'storage'.DIRECTORY_SEPARATOR.'efinanc_web.cer');
        }
    }
    
    /**
     * Load Soap Class
     * @param SoapInterface $soap
     */
    public function loadSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
    }
    
    /**
     * Returns a string not subject to repetition indicating
     * the year, month, day, hour, minute, and second
     * This return is used to name the log files of the
     * SOAP communications sent and returned
     * @return string AAAMMDDHHMMSS
     */
    protected function generateMark()
    {
        return date('YmdHis');
    }
    
    /**
     * This method communicates with the webservice by sending the
     * pre-mounted message to the designated URL
     * @param string $body
     * @param string $method
     * @param string $url
     * @return string
     */
    protected function sendRequest($body, $method, $url)
    {
        if (!is_object($this->soap)) {
            $this->soap = new SoapCurl($this->certificate);
        }
        $request = "<soapenv:Envelope ";
        foreach ($this->soapnamespaces as $key => $xmlns) {
            $request .= "$key=\"$xmlns\" ";
        }
        $action = $this->soapnamespaces['xmlns:sped']."$method";
        $request .= ">"
            . "<soapenv:Header/>"
            . "<soapenv:Body>"
            . $body
            . "</soapenv:Body>"
            . "</soapenv:Envelope>";
        
        $msgSize = strlen($request);
        $parameters = [
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"$action\"",
            "Content-length: $msgSize"
        ];
        $this->request = $request;
        $this->response = $this->soap->send(
            $method,
            $url,
            $action,
            $request,
            $parameters
        );
        return $this->response;
    }
    
    /**
     * Includes missing or unsupported properties in stdClass
     * @param stdClass $std
     * @param array $possible
     * @return stdClass
     */
    protected function equilizeParameters(stdClass $std, $possible)
    {
        $arr = get_object_vars($std);
        foreach ($possible as $key) {
            if (!array_key_exists($key, $arr)) {
                $std->$key = null;
            }
        }
        return $std;
    }
}
