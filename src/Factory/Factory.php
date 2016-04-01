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
     * Executa a assinatura digital do xml
     */
    public function assina()
    {
        $this->xml = $this->pkcs->signXML($this->xml, $this->signTag);
    }
   
    
    /**
     * Construtor do XML
     */
    abstract public function monta();
}
