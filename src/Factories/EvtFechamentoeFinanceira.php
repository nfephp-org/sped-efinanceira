<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtFechamentoeFinanceira extends Factory implements FactoryInterface
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
        $params->evtName = 'evtFechamentoeFinanceira';
        $params->evtTag = 'evtFechamentoeFinanceira';
        $params->evtAlias = 'F-4000';
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

        $infoFechamento = $this->dom->createElement("infoFechamento");
        $this->dom->addChild(
            $infoFechamento,
            "dtInicio",
            $this->std->dtinicio,
            true
        );
        $this->dom->addChild(
            $infoFechamento,
            "dtFim",
            $this->std->dtfim,
            true
        );
        $this->dom->addChild(
            $infoFechamento,
            "sitEspecial",
            $this->std->sitespecial,
            true
        );
        //campo novo 1.3.0
        $this->dom->addChild(
            $infoFechamento,
            "nadaADeclarar",
            $this->std->nadaadeclarar ?? null,
            false
        );
        $this->node->insertBefore($infoFechamento);

        if (!empty($this->std->fechamentopp)) {
            $fpp = $this->std->fechamentopp;
            $FechamentoPP = $this->dom->createElement("FechamentoPP");
            $this->dom->addChild(
                $FechamentoPP,
                "FechamentoPP",
                $fpp->movimento,
                true
            );
            $this->node->appendChild($FechamentoPP);
        }

        if (!empty($this->std->fechamentomovopfin)) {
            $opfin = $this->std->fechamentomovopfin;
            $FechamentoMovOpFin = $this->dom->createElement("FechamentoMovOpFin");
            $this->dom->addChild(
                $FechamentoMovOpFin,
                "FechamentoMovOpFin",
                $opfin->movimento,
                true
            );

            if (!empty($opfin->entdecexterior)) {
                $EntDecExterior = $this->dom->createElement("EntDecExterior");
                $this->dom->addChild(
                    $EntDecExterior,
                    "ContasAReportar",
                    $opfin->entdecexterior->contasareportar,
                    true
                );
                $FechamentoMovOpFin->appendChild($EntDecExterior);
            }
            if (!empty($opfin->entpatdecexterior)) {
                foreach ($opfin->entpatdecexterior as $ex) {
                    $EntPatDecExterior = $this->dom->createElement("EntPatDecExterior");
                    $this->dom->addChild(
                        $EntPatDecExterior,
                        "GIIN",
                        $ex->giin,
                        true
                    );
                    $this->dom->addChild(
                        $EntPatDecExterior,
                        "CNPJ",
                        $ex->cnpj,
                        true
                    );
                    $this->dom->addChild(
                        $EntPatDecExterior,
                        "ContasAReportar",
                        $ex->contasareportar,
                        true
                    );
                    $this->dom->addChild(
                        $EntPatDecExterior,
                        "inCadPatrocinadoEncerrado",
                        !empty($ex->incadpatrocinadoencerrado) ? $ex->incadpatrocinadoencerrado : null,
                        false
                    );
                    $this->dom->addChild(
                        $EntPatDecExterior,
                        "inGIINEncerrado",
                        !empty($ex->ingiinencerrado) ? $ex->ingiinencerrado : null,
                        false
                    );
                    $FechamentoMovOpFin->appendChild($EntPatDecExterior);
                }
            }
            $this->node->appendChild($FechamentoMovOpFin);
        }

        if (!empty($this->std->fechamentomovopfinanual)) {
            $f = $this->std->fechamentomovopfinanual;
            $fechaAno = $this->dom->createElement("FechamentoMovOpFinAnual");
            $this->dom->addChild(
                $fechaAno,
                "FechamentoMovOpFinAnual",
                $f->movimento,
                true
            );
            $this->node->appendChild($fechaAno);
        }
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
