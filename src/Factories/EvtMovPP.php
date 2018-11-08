<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtMovPP extends Factory implements FactoryInterface
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
        $params->evtName = 'evtMovPP';
        $params->evtTag = 'evtMovPP';
        $params->evtAlias = 'F-6000';
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
        
        $ideDeclarado = $this->dom->createElement("ideDeclarado");
        $this->dom->addChild(
            $ideDeclarado,
            "tpNI",
            $this->std->tpni,
            true
        );
        $this->dom->addChild(
            $ideDeclarado,
            "tpDeclarado",
            !empty($this->std->tpdeclarado) ? $this->std->tpdeclarado : null,
            false
        );
        $this->dom->addChild(
            $ideDeclarado,
            "NIDeclarado",
            $this->std->nideclarado,
            true
        );
        
        $mesCaixa = $this->dom->createElement("mesCaixa");
        $this->dom->addChild(
            $mesCaixa,
            "anoMesCaixa",
            $this->std->anomescaixa,
            true
        );
        foreach ($this->std->infoprevpriv as $infp) {
            $infoPrevPriv = $this->dom->createElement("infoPrevPriv");
            $this->dom->addChild(
                $infoPrevPriv,
                "numProposta",
                !empty($infp->numproposta) ? $infp->numproposta : null,
                false
            );
            $this->dom->addChild(
                $infoPrevPriv,
                "numProcesso",
                !empty($infp->numprocesso) ? $infp->numprocesso : null,
                false
            );
            $this->dom->addChild(
                $infoPrevPriv,
                "tpProduto",
                !empty($infp->tpproduto) ? $infp->tpproduto : null,
                false
            );
            $this->dom->addChild(
                $infoPrevPriv,
                "tpPlano",
                !empty($infp->tpplano) ? $infp->tpplano : null,
                false
            );
            
            $opPrevPriv = $this->dom->createElement("opPrevPriv");
            $saldoInicial = $this->dom->createElement("saldoInicial");
            $this->dom->addChild(
                $saldoInicial,
                "vlrPrincipal",
                number_format($infp->vlrprincipal, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $saldoInicial,
                "vlrRendimentos",
                number_format($infp->vlrrendimentos, 2, ',', ''),
                true
            );
            $opPrevPriv->appendChild($saldoInicial);
            
            if (!empty($infp->aplic)) {
                foreach ($infp->aplic as $ap) {
                    $aplic = $this->dom->createElement("aplic");
                    $this->dom->addChild(
                        $aplic,
                        "vlrContribuicao",
                        number_format($ap->vlrcontribuicao, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $aplic,
                        "vlrCarregamento",
                        number_format($ap->vlrcarregamento, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $aplic,
                        "vlrPartPF",
                        number_format($ap->vlrpartpf, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $aplic,
                        "vlrPartPJ",
                        number_format($ap->vlrpartpj, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $aplic,
                        "cnpj",
                        $ap->cnpj,
                        true
                    );
                    $opPrevPriv->appendChild($aplic);
                }
            }
            if (!empty($infp->resg)) {
                foreach ($infp->resg as $rs) {
                    $resg = $this->dom->createElement("resg");
                    $this->dom->addChild(
                        $resg,
                        "vlrAliquotaIRRF",
                        number_format($rs->vlraliquotairrf, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $resg,
                        "numAnosCarencia",
                        number_format($rs->numanoscarencia, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $resg,
                        "vlrResgatePrincipal",
                        number_format($rs->vlrresgateprincipal, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $resg,
                        "vlrResgateRendimentos",
                        number_format($rs->vlrresgaterendimentos, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $resg,
                        "vlrIRRF",
                        number_format($rs->vlrirrf, 2, ',', ''),
                        true
                    );
                    $opPrevPriv->appendChild($resg);
                }
            }
            
            if (!empty($infp->benef)) {
                foreach ($infp->benef as $bn) {
                    $benef = $this->dom->createElement("benef");
                    $this->dom->addChild(
                        $benef,
                        "tpNI",
                        $bn->tpni,
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "NIParticipante",
                        $bn->niparticipante,
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "CodReceita",
                        $bn->codreceita,
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "prazovigencia",
                        $bn->prazovigencia,
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "vlrMensalInicial",
                        number_format($bn->vlrmensalinicial, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "vlrBruto",
                        number_format($bn->vlrbruto, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "vlrLiquido",
                        number_format($bn->vlrliquido, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "vlrIRRF",
                        number_format($bn->vlrirrf, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "vlrAliquotaIRRF",
                        number_format($bn->vlraliquotairrf, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $benef,
                        "competenciaPgto",
                        $bn->competenciapgto,
                        true
                    );
                    $opPrevPriv->appendChild($benef);
                }
                
                $saldoFinal = $this->dom->createElement("saldoFinal");
                $this->dom->addChild(
                    $saldoFinal,
                    "vlrPrincipal",
                    number_format($infp->saldofinal->vlrprincipal, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $saldoFinal,
                    "vlrRendimentos",
                    number_format($infp->saldofinal->vlrrendimentos, 2, ',', ''),
                    true
                );
                $opPrevPriv->appendChild($saldoFinal);
            }
            
            $infoPrevPriv->appendChild($opPrevPriv);
            
            $mesCaixa->appendChild($infoPrevPriv);
        }
        $this->node->appendChild($mesCaixa);
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
