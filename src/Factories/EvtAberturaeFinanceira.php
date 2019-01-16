<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtAberturaeFinanceira extends Factory implements FactoryInterface
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
        $params->evtName = 'evtAberturaeFinanceira';
        $params->evtTag = 'evtAberturaeFinanceira';
        $params->evtAlias = 'F-1000';
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
        
        $infoAbertura = $this->dom->createElement("infoAbertura");
        $this->dom->addChild(
            $infoAbertura,
            "dtInicio",
            $this->std->dtinicio,
            true
        );
        $this->dom->addChild(
            $infoAbertura,
            "dtFim",
            $this->std->dtfim,
            true
        );
        $this->node->appendChild($infoAbertura);
        
        if (!empty($this->std->aberturapp)) {
            $AberturaPP = $this->dom->createElement("AberturaPP");
            $pp = $this->std->aberturapp;
            foreach ($pp->tpempresa as $tpe) {
                $tpEmpresa = $this->dom->createElement("tpEmpresa");
                $this->dom->addChild(
                    $tpEmpresa,
                    "tpPrevPriv",
                    $tpe->tpprevpriv,
                    true
                );
                $AberturaPP->appendChild($tpEmpresa);
            }
            $this->node->appendChild($AberturaPP);
        }
        
        if (!empty($this->std->aberturamovopfin)) {
            $AberturaMovOpFin = $this->dom->createElement("AberturaMovOpFin");
            $rmf = $this->std->aberturamovopfin->responsavelrmf;
            $ResponsavelRMF = $this->dom->createElement("ResponsavelRMF");
            $this->dom->addChild(
                $ResponsavelRMF,
                "CPF",
                $rmf->cpf,
                true
            );
            $this->dom->addChild(
                $ResponsavelRMF,
                "Nome",
                $rmf->nome,
                true
            );
            $this->dom->addChild(
                $ResponsavelRMF,
                "Setor",
                $rmf->setor,
                true
            );
            $Telefone = $this->dom->createElement("Telefone");
            $this->dom->addChild(
                $Telefone,
                "DDD",
                $rmf->telefone->ddd,
                true
            );
            $this->dom->addChild(
                $Telefone,
                "Numero",
                $rmf->telefone->numero,
                true
            );
            $this->dom->addChild(
                $Telefone,
                "Ramal",
                isset($rmf->telefone->ramal) ? $rmf->telefone->ramal : null,
                false
            );
            $ResponsavelRMF->appendChild($Telefone);
            $end = $rmf->endereco;
            $endereco = $this->dom->createElement("Endereco");
            $this->dom->addChild(
                $endereco,
                "Logradouro",
                $end->logradouro,
                true
            );
            $this->dom->addChild(
                $endereco,
                "Numero",
                $end->numero,
                true
            );
            $this->dom->addChild(
                $endereco,
                "Complemento",
                isset($end->complemento) ? $end->complemento : null,
                false
            );
            $this->dom->addChild(
                $endereco,
                "Bairro",
                $end->bairro,
                true
            );
            $this->dom->addChild(
                $endereco,
                "CEP",
                $end->cep,
                true
            );
            $this->dom->addChild(
                $endereco,
                "Municipio",
                $end->municipio,
                true
            );
            $this->dom->addChild(
                $endereco,
                "UF",
                $end->uf,
                true
            );
            $ResponsavelRMF->appendChild($endereco);
            $AberturaMovOpFin->appendChild($ResponsavelRMF);
            foreach ($this->std->aberturamovopfin->respefin as $rf) {
                $RespeFin = $this->dom->createElement("RespeFin");
                $this->dom->addChild(
                    $RespeFin,
                    "CPF",
                    $rf->cpf,
                    true
                );
                $this->dom->addChild(
                    $RespeFin,
                    "Nome",
                    $rf->nome,
                    true
                );
                $this->dom->addChild(
                    $RespeFin,
                    "Setor",
                    $rf->setor,
                    true
                );
                $Telefone = $this->dom->createElement("Telefone");
                $this->dom->addChild(
                    $Telefone,
                    "DDD",
                    $rf->telefone->ddd,
                    true
                );
                $this->dom->addChild(
                    $Telefone,
                    "Numero",
                    $rf->telefone->numero,
                    true
                );
                $this->dom->addChild(
                    $Telefone,
                    "Ramal",
                    isset($rf->telefone->ramal) ? $rf->telefone->ramal : null,
                    false
                );
                $RespeFin->appendChild($Telefone);
                $end = $rf->endereco;
                $endereco = $this->dom->createElement("Endereco");
                $this->dom->addChild(
                    $endereco,
                    "Logradouro",
                    $end->logradouro,
                    true
                );
                $this->dom->addChild(
                    $endereco,
                    "Numero",
                    $end->numero,
                    true
                );
                $this->dom->addChild(
                    $endereco,
                    "Complemento",
                    isset($end->complemento) ? $end->complemento : null,
                    false
                );
                $this->dom->addChild(
                    $endereco,
                    "Bairro",
                    $end->bairro,
                    true
                );
                $this->dom->addChild(
                    $endereco,
                    "CEP",
                    $end->cep,
                    true
                );
                $this->dom->addChild(
                    $endereco,
                    "Municipio",
                    $end->municipio,
                    true
                );
                $this->dom->addChild(
                    $endereco,
                    "UF",
                    $end->uf,
                    true
                );
                $RespeFin->appendChild($endereco);
                $this->dom->addChild(
                    $RespeFin,
                    "Email",
                    $rf->email,
                    true
                );
                $AberturaMovOpFin->appendChild($RespeFin);
            }
            
            $RepresLegal = $this->dom->createElement("RepresLegal");
            $rl = $this->std->aberturamovopfin->represlegal;
            $this->dom->addChild(
                $RepresLegal,
                "CPF",
                $rl->cpf,
                true
            );
            $this->dom->addChild(
                $RepresLegal,
                "Setor",
                $rl->setor,
                true
            );
            $Telefone = $this->dom->createElement("Telefone");
            $this->dom->addChild(
                $Telefone,
                "DDD",
                $rl->telefone->ddd,
                true
            );
            $this->dom->addChild(
                $Telefone,
                "Numero",
                $rl->telefone->numero,
                true
            );
            $this->dom->addChild(
                $Telefone,
                "Ramal",
                isset($rl->telefone->ramal) ? $rl->telefone->ramal : null,
                false
            );
            $RepresLegal->appendChild($Telefone);
            $AberturaMovOpFin->appendChild($RepresLegal);
            
            $this->node->appendChild($AberturaMovOpFin);
        }
        
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
