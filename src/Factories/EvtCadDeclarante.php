<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtCadDeclarante extends Factory implements FactoryInterface
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
        $params->evtName = 'evtCadDeclarante';
        $params->evtTag = 'evtCadDeclarante';
        $params->evtAlias = 'F-2000';
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
        
        $infoCadastro = $this->dom->createElement("infoCadastro");
        $info = $this->std->infocadastro;
        $this->dom->addChild(
            $infoCadastro,
            "GIIN",
            isset($info->giin) ? $info->giin : null,
            false
        );
        $this->dom->addChild(
            $infoCadastro,
            "CategoriaDeclarante",
            isset($info->categoriadeclarante) ? $info->categoriadeclarante : null,
            false
        );
        if (!empty($info->nif)) {
            foreach ($info->nif as $n) {
                $NIF = $this->dom->createElement("NIF");
                $this->dom->addChild(
                    $NIF,
                    "NumeroNIF",
                    $n->numeronif,
                    true
                );
                $this->dom->addChild(
                    $NIF,
                    "PaisEmissao",
                    $n->paisemissao,
                    true
                );
                $this->dom->addChild(
                    $NIF,
                    "tpNIF",
                    isset($n->tpnif) ? $n->tpnif : null,
                    false
                );
                $infoCadastro->appendChild($NIF);
            }
        }
        $this->dom->addChild(
            $infoCadastro,
            "nome",
            $info->nome,
            true
        );
        $this->dom->addChild(
            $infoCadastro,
            "tpNome",
            isset($info->tpnome) ? $info->tpnome : null,
            false
        );
        $this->dom->addChild(
            $infoCadastro,
            "enderecoLivre",
            $info->enderecolivre,
            true
        );
        $this->dom->addChild(
            $infoCadastro,
            "tpEndereco",
            isset($info->tpendereco) ? $info->tpendereco : null,
            false
        );
        $this->dom->addChild(
            $infoCadastro,
            "municipio",
            $info->municipio,
            true
        );
        $this->dom->addChild(
            $infoCadastro,
            "UF",
            $info->uf,
            true
        );
        $this->dom->addChild(
            $infoCadastro,
            "CEP",
            $info->cep,
            true
        );
        $this->dom->addChild(
            $infoCadastro,
            "Pais",
            $info->pais,
            true
        );
        foreach ($info->paisresid as $p) {
            $paisResid = $this->dom->createElement("paisResid");
            $this->dom->addChild(
                $paisResid,
                "Pais",
                $p->pais,
                true
            );
            $infoCadastro->appendChild($paisResid);
        }
        if (!empty($info->enderecooutros)) {
            foreach ($info->enderecooutros as $out) {
                $EnderecoOutros = $this->dom->createElement("EnderecoOutros");
                $this->dom->addChild(
                    $EnderecoOutros,
                    "tpEndereco",
                    isset($out->tpendereco) ? $out->tpendereco : null,
                    false
                );
                $this->dom->addChild(
                    $EnderecoOutros,
                    "EnderecoLivre",
                    isset($out->enderecolivre) ? $out->enderecolivre : null,
                    false
                );
                if (!empty($out->enderecoestrutura) && empty($out->enderecolivre)) {
                    $ee = $out->enderecoestrutura;
                    $EnderecoEstrutura = $this->dom->createElement("EnderecoEstrutura");
                    $this->dom->addChild(
                        $EnderecoEstrutura,
                        "EnderecoLivre",
                        isset($ee->enderecolivre) ? $ee->enderecolivre : null,
                        false
                    );
                    if (!empty($ee->endereco)) {
                        $end = $ee->endereco;
                        $Endereco = $this->dom->createElement("Endereco");
                        $this->dom->addChild(
                            $Endereco,
                            "Logradouro",
                            isset($end->logradouro) ? $end->logradouro : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Numero",
                            isset($end->numero) ? $end->numero : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Complemento",
                            isset($end->complemento) ? $end->complemento : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Andar",
                            isset($end->andar) ? $end->andar : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Bairro",
                            isset($end->bairro) ? $end->bairro : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "CaixaPostal",
                            isset($end->caixapostal) ? $end->caixapostal : null,
                            false
                        );
                        $EnderecoEstrutura->appendChild($Endereco);
                    }
                    $this->dom->addChild(
                        $EnderecoEstrutura,
                        "CEP",
                        $ee->cep,
                        true
                    );
                    $this->dom->addChild(
                        $EnderecoEstrutura,
                        "Municipio",
                        $ee->municipio,
                        true
                    );
                    $this->dom->addChild(
                        $EnderecoEstrutura,
                        "UF",
                        $ee->uf,
                        true
                    );
                    $EnderecoOutros->appendChild($EnderecoEstrutura);
                }
                $this->dom->addChild(
                    $EnderecoOutros,
                    "Pais",
                    $out->pais,
                    true
                );
                $infoCadastro->appendChild($EnderecoOutros);
            }
        }
        $this->node->appendChild($infoCadastro);
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
