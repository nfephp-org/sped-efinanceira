<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtCadIntermediario extends Factory implements FactoryInterface
{
    /**
     * Constructor
     * @param string $config
     * @param stdClass $std
     * @param Certificate $certificate
     * @param string $data
     */
    public function __construct(
        $config,
        stdClass $std,
        Certificate $certificate = null,
        $data = ''
    ) {
        $params = new \stdClass();
        $params->evtName = 'evtCadIntermediario';
        $params->evtTag = 'evtCadIntermediario';
        $params->evtAlias = 'F-2010';
        parent::__construct($config, $std, $params, $certificate, $data);
    }

    protected function toNode()
    {
        $ideDeclarante = $this->node->getElementsByTagName('ideDeclarante')->item(0);
        //o idEvento pode variar de evento para evento
        //então cada factory individualmente terá de construir o seu
        $ideEvento = $this->dom->createElement("ideEvento");
        $this->dom->addChild(
            $ideEvento,
            "indRetificacao",
            $this->std->indretificacao,
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "nrRecibo",
            isset($this->std->nrrecibo) ? $this->std->nrrecibo : null,
            false
        );
        $this->dom->addChild(
            $ideEvento,
            "tpAmb",
            (string) $this->tpAmb,
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "aplicEmi",
            '1',
            true
        );
        $this->dom->addChild(
            $ideEvento,
            "verAplic",
            $this->verAplic,
            true
        );
        $this->node->insertBefore($ideEvento, $ideDeclarante);
        
        $infoIntermediario = $this->dom->createElement("infoIntermediario");
        $this->dom->addChild(
            $infoIntermediario,
            "GIIN",
            !empty($this->std->giin) ? $this->std->giin : null,
            false
        );
        $this->dom->addChild(
            $infoIntermediario,
            "tpNI",
            !empty($this->std->tpni) ? $this->std->tpni : null,
            false
        );
        $this->dom->addChild(
            $infoIntermediario,
            "NIIntermediario",
            !empty($this->std->niintermediario) ? $this->std->niintermediario : null,
            false
        );
        $this->dom->addChild(
            $infoIntermediario,
            "nomeIntermediario",
            $this->std->nomeintermediario,
            true
        );
        $endereco = $this->dom->createElement("endereco");
        $this->dom->addChild(
            $endereco,
            "enderecoLivre",
            $this->std->endereco->enderecolivre,
            true
        );
        $this->dom->addChild(
            $endereco,
            "municipio",
            $this->std->endereco->municipio,
            true
        );
        $this->dom->addChild(
            $endereco,
            "pais",
            $this->std->endereco->pais,
            true
        );
        $infoIntermediario->appendChild($endereco);
        $this->dom->addChild(
            $infoIntermediario,
            "paisResidencia",
            $this->std->paisresidencia,
            true
        );
        $this->node->appendChild($infoIntermediario);
        
        
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
