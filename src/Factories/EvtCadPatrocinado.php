<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtCadPatrocinado extends Factory implements FactoryInterface
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
        $params->evtName = 'evtCadPatrocinado';
        $params->evtTag = 'evtCadPatrocinado';
        $params->evtAlias = 'F-2020';
        parent::__construct($config, $std, $params, $certificate, $data);
    }

    protected function toNode()
    {
        $ideDeclarante = $this->node->getElementsByTagName('ideDeclarante')->item(0);
        $this->dom->addChild(
            $ideDeclarante,
            "GIIN",
            !empty($this->std->giin) ? $this->std->giin : null,
            false
        );
        $this->dom->addChild(
            $ideDeclarante,
            "CategoriaPatrocinador",
            !empty($this->std->categoriapatrocinador) ? $this->std->categoriapatrocinador : null,
            false
        );
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
        
        $ip = $this->std->infopatrocinado;
        $infoPatrocinado = $this->dom->createElement("infoPatrocinado");
        $this->dom->addChild(
            $infoPatrocinado,
            "GIIN",
            !empty($ip->giin) ? $ip->giin : null,
            false
        );
        $this->dom->addChild(
            $infoPatrocinado,
            "CNPJ",
            $ip->cnpj,
            true
        );
        if (!empty($ip->nif)) {
            foreach ($ip->nif as $n) {
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
                    !empty($n->tpnif) ? $n->tpnif : null,
                    false
                );


                $infoPatrocinado->appendChild($NIF);
            }
        }
        $this->dom->addChild(
            $infoPatrocinado,
            "nomePatrocinado",
            $ip->nomepatrocinado,
            true
        );
        $this->dom->addChild(
            $infoPatrocinado,
            "tpNome",
            !empty($ip->tpnome) ? $ip->tpnome : null,
            false
        );
        
        $end = $ip->endereco;
        $endereco = $this->dom->createElement("endereco");
        $this->dom->addChild(
            $endereco,
            "enderecoLivre",
            $end->enderecolivre,
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
            "municipio",
            $end->municipio,
            true
        );
        $this->dom->addChild(
            $endereco,
            "pais",
            $end->pais,
            true
        );
        $infoPatrocinado->appendChild($endereco);
        $this->dom->addChild(
            $infoPatrocinado,
            "tpEndereco",
            !empty($ip->tpendereco) ? $ip->tpendereco : null,
            false
        );
        
        if (!empty($ip->enderecooutros)) {
            foreach ($ip->enderecooutros as $eo) {
                $EnderecoOutros = $this->dom->createElement("EnderecoOutros");
                $this->dom->addChild(
                    $EnderecoOutros,
                    "tpEndereco",
                    !empty($eo->tpendereco) ? $eo->tpendereco : null,
                    false
                );
                $this->dom->addChild(
                    $EnderecoOutros,
                    "EnderecoLivre",
                    !empty($eo->enderecolivre) ? $eo->enderecolivre : null,
                    false
                );
                if (!empty($eo->enderecoestrutura) && empty($eo->enderecolivre)) {
                    $ee = $eo->enderecoestrutura;
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
                    $eo->pais,
                    true
                );
                $infoPatrocinado->appendChild($EnderecoOutros);
            }
        }
        foreach ($ip->paisresid as $pr) {
            $paisResid = $this->dom->createElement("paisResid");
            $this->dom->addChild(
                $paisResid,
                "Pais",
                $pr->pais,
                true
            );
            $infoPatrocinado->appendChild($paisResid);
        }
        
        $this->node->appendChild($infoPatrocinado);
        
        
        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
