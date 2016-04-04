<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe abstrata construtora dos eventos
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\Factory
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\Common\Base\BaseMake;
use NFePHP\Common\Certificate\Pkcs12;
use NFePHP\Common\Files\FilesFolders;

abstract class Factory extends BaseMake
{
    /**
     * Objeto stdClass convertido do Json config
     * @var stdClass
     */
    protected $objConfig;
    /**
     * estabelece qual a tag será assinada
     * deve estar preenchido nas classes derivadas
     * @var string
     */
    protected $signTag = '';
    /**
     * Objeto Dom::class Tag ideDeclarante
     * @var Dom
     */
    protected $ide;
    /**
     * Objeto Dom::class Tag evt???
     * @var Dom
     */
    protected $evt;
    /**
     * Instancia da classe que lida com os certificados
     * será usada na assinatura do xml dos eventos
     * @var Pkcs12
     */
    protected $pkcs;


    /**
     * Recebe o arquivo de configuração em uma string json ou em um path de arquivo
     *
     * @param string $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->loadConfig($config);
    }
    
    /**
     * Execura a leitura do arquivo de configuração e
     * carrega o certificado
     *
     * @param string $config
     */
    protected function loadConfig($config)
    {
        if (is_file($config)) {
            $config = FilesFolders::readFile($config);
        }
        $result = json_decode($config);
        if (json_last_error() === JSON_ERROR_NONE) {
            $this->objConfig = $result;
        }
        $this->pkcs = new Pkcs12($this->objConfig->pathCertsFiles, $this->objConfig->cnpj);
        $this->pkcs->loadPfxFile($this->objConfig->pathCertsFiles.$this->objConfig->certPfxName, $this->objConfig->certPassword, true, false, false);
    }
    
    /**
     * Cria a tag evt????
     *
     * @param string $id
     * @param int $indRetificacao
     * @param int $tpAmb
     * @param string $nrRecibo
     * @return Dom
     */
    public function tagEvento($id, $indRetificacao, $tpAmb, $nrRecibo = '')
    {
        $id = "ID".str_pad($id, 18, '0', STR_PAD_LEFT);
        $identificador = 'tag raiz ';
        $this->evt = $this->dom->createElement($this->signTag);
        //importante a identificação "Id" deve estar grafada assim
        $this->evt->setAttribute("Id", $id);
        $ide = $this->dom->createElement("ideEvento");
        $this->dom->addChild(
            $ide,
            "indRetificacao",
            $indRetificacao,
            true,
            $identificador . "Indicador de retificação"
        );
        if ($indRetificacao > 1) {
            $this->dom->addChild(
                $ide,
                "nrRecibo",
                $nrRecibo,
                true,
                $identificador . "Numero do recibo quando for retificador"
            );
        }
        $this->dom->addChild(
            $ide,
            "tpAmb",
            $tpAmb,
            true,
            $identificador . "tipo de ambiente"
        );
        $this->dom->addChild(
            $ide,
            "aplicEmi",
            $this->objConfig->aplicEmi,
            true,
            $identificador . "Aplicativo de emissão do evento"
        );
        $this->dom->addChild(
            $ide,
            "verAplic",
            $this->objConfig->verAplic,
            true,
            $identificador . "Versão do aplicativo de emissão do evento"
        );
        $this->dom->appChild($this->evt, $ide);
        return $this->evt;
    }
    
    /**
     * Cria a tag ideDeclarante
     *
     * @param string $cnpj
     * @return Dom
     */
    public function tagDeclarante($cnpj)
    {
        $identificador = 'tag ideDeclarante ';
        $ide = $this->dom->createElement("ideDeclarante");
        $this->dom->addChild(
            $ide,
            "cnpjDeclarante",
            $cnpj,
            true,
            $identificador . "Informar CNPJ da Empresa Declarante"
        );
        $this->ide = $ide;
        return $ide;
    }
    
    /**
     * Executa a assinatura digital do xml
     */
    public function assina()
    {
        $this->xml = $this->pkcs->signXML($this->xml, $this->signTag);
    }

    /**
     * Construtor do XML
     * Executa a montagem geral do xml de evento
     */
    public function monta()
    {
        $this->eFinanceira = $this->dom->createElement("eFinanceira");
        $this->eFinanceira->setAttribute("xmlns", "http://www.eFinanceira.gov.br/schemas/".$this->signTag."/".$this->objConfig->schemes);
        $this->dom->appChild($this->evt, $this->ide, "Falta CadDeclarante");
        $this->dom->appChild($this->evt, $this->info, "Falta CadDeclarante");
        
        $this->premonta();
        
        $this->dom->appChild($this->eFinanceira, $this->evt, 'Falta DOMDocument');
        $this->dom->appChild($this->dom, $this->eFinanceira, 'Falta DOMDocument');
        $this->xml = $this->dom->saveXML();
    }    
    
    /**
     * preConstrutor do XML
     * Essa função faz uma pre-montagem de estruturas particulares a cada evento
     */
    abstract protected function premonta();
}
