<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtRERCT extends Factory implements FactoryInterface
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
        $params->evtName = 'evtRERCT';
        $params->evtTag = 'evtRERCT';
        $params->evtAlias = 'F-8000';
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
            "ideEventoRERCT",
            $this->std->ideeventorerct,
            true
        );
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
        
        $ideDeclarado = $this->dom->createElement("ideDeclarado");
        $cpfCnpjDeclarado = $this->dom->createElement("cpfCnpjDeclarado");
        $this->dom->addChild(
            $cpfCnpjDeclarado,
            "tpInscr",
            $this->std->idedeclarado->tpinscr,
            true
        );
        $this->dom->addChild(
            $cpfCnpjDeclarado,
            "nrInscr",
            $this->std->idedeclarado->nrinscr,
            true
        );
        $ideDeclarado->appendChild($cpfCnpjDeclarado);
        $this->node->appendChild($ideDeclarado);
        
        if (!empty($this->std->rerct)) {
            foreach ($this->std->rerct as $r) {
                $RERCT = $this->dom->createElement("RERCT");
                $this->dom->addChild(
                    $RERCT,
                    "nomeBancoOrigem",
                    !empty($r->nomebancoorigem) ? $r->nomebancoorigem : null,
                    false
                );
                $this->dom->addChild(
                    $RERCT,
                    "paisOrigem",
                    !empty($r->paisorigem) ? $r->paisorigem : null,
                    false
                );
                $this->dom->addChild(
                    $RERCT,
                    "BICBancoOrigem",
                    !empty($r->bicbancoorigem) ? $r->bicbancoorigem : null,
                    false
                );
                if (!empty($r->infocontaexterior)) {
                    foreach ($r->infocontaexterior as $ce) {
                        $infoContaExterior = $this->dom->createElement("infoContaExterior");
                        if (!empty($ce->titular)) {
                            foreach ($ce->titular as $t) {
                                $titular = $this->dom->createElement("titular");
                                $this->dom->addChild(
                                    $titular,
                                    "nomeTitular",
                                    !empty($t->nometitular) ? $t->nometitular : null,
                                    false
                                );
                                if (!empty($t->tpinsc) && !empty($t->nrinsc)) {
                                    $cpfCnpjTitular = $this->dom->createElement("cpfCnpjTitular");
                                    $this->dom->addChild(
                                        $cpfCnpjTitular,
                                        "tpInscr",
                                        $t->tpinscr,
                                        true
                                    );
                                    $this->dom->addChild(
                                        $cpfCnpjTitular,
                                        "nrInsc",
                                        $t->nrinscr,
                                        true
                                    );
                                    $titular->appendChild($cpfCnpjTitular);
                                }
                                $this->dom->addChild(
                                    $titular,
                                    "NIFTitular",
                                    !empty($t->niftitular) ? $t->niftitular : null,
                                    false
                                );
                                $infoContaExterior->appendChild($titular);
                            }
                        }
                        if (!empty($ce->beneficiariofinal)) {
                            foreach ($ce->beneficiariofinal as $t) {
                                $beneficiariofinal = $this->dom->createElement("beneficiarioFinal");
                                $this->dom->addChild(
                                    $beneficiariofinal,
                                    "nomeBeneficiarioFinal",
                                    !empty($t->nomebeneficiariofinal) ? $t->nomebeneficiariofinal : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $beneficiariofinal,
                                    "cpfBeneficiarioFinal",
                                    !empty($t->cpfbeneficiariofinal) ? $t->cpfbeneficiariofinal : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $beneficiariofinal,
                                    "NIFBeneficiarioFinal",
                                    !empty($t->nifbeneficiariofinal) ? $t->nifbeneficiariofinal : null,
                                    false
                                );
                                $infoContaExterior->appendChild($beneficiariofinal);
                            }
                        }
                        
                        $this->dom->addChild(
                            $infoContaExterior,
                            "tpContaExterior",
                            !empty($ce->tpcontaexterior) ? $ce->tpcontaexterior : null,
                            false
                        );
                        $this->dom->addChild(
                            $infoContaExterior,
                            "nrContaExterior",
                            !empty($ce->nrcontaexterior) ? $ce->nrcontaexterior : null,
                            false
                        );
                        $this->dom->addChild(
                            $infoContaExterior,
                            "vlrUltDia",
                            !empty($ce->vlrultdia) ? number_format($ce->vlrultdia, 2, ",", "") : null,
                            false
                        );
                        $this->dom->addChild(
                            $infoContaExterior,
                            "moeda",
                            !empty($ce->moeda) ? $ce->moeda : null,
                            false
                        );
                        $RERCT->appendChild($infoContaExterior);
                    }
                }
                $this->node->appendChild($RERCT);
            }
        }
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
