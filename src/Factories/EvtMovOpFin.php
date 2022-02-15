<?php

namespace NFePHP\eFinanc\Factories;

use NFePHP\eFinanc\Common\Factory;
use NFePHP\eFinanc\Common\FactoryInterface;
use NFePHP\eFinanc\Common\FactoryId;
use NFePHP\Common\Certificate;
use stdClass;

class EvtMovOpFin extends Factory implements FactoryInterface
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
        $params->evtName = 'evtMovOpFin';
        $params->evtTag = 'evtMovOpFin';
        $params->evtAlias = 'F-3000';
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
        if (!empty($this->std->nif)) {
            foreach ($this->std->nif as $n) {
                $NIF = $this->dom->createElement("NIF");
                $this->dom->addChild(
                    $NIF,
                    "NumeroNIF",
                    $n->numeronif,
                    true
                );
                $this->dom->addChild(
                    $NIF,
                    "PaisEmissaoNIF",
                    $n->paisemissaonif,
                    true
                );
                $this->dom->addChild(
                    $NIF,
                    "tpNIF",
                    !empty($n->tpnif) ? $n->tpnif : null,
                    false
                );
                $ideDeclarado->appendChild($NIF);
            }
        }
        $this->dom->addChild(
            $ideDeclarado,
            "NomeDeclarado",
            $this->std->nomedeclarado,
            true
        );
        $this->dom->addChild(
            $ideDeclarado,
            "tpNomeDeclarado",
            !empty($this->std->tpnomedeclarado) ? $this->std->tpnomedeclarado : null,
            false
        );
        if ($this->std->nomeoutros) {
            foreach ($this->std->nomeoutros as $no) {
                $NomeOutros = $this->dom->createElement("NomeOutros");
                if (!empty($no->nomepf)) {
                    $npf = $no->nomepf;
                    $NomePF = $this->dom->createElement("NomePF");
                    $this->dom->addChild(
                        $NomePF,
                        "tpNome",
                        !empty($npf->tpnome) ? $npf->tpnome : null,
                        false
                    );
                    $this->dom->addChild(
                        $NomePF,
                        "PrecTitulo",
                        !empty($npf->prectitulo) ? $npf->prectitulo : null,
                        false
                    );
                    $this->dom->addChild(
                        $NomePF,
                        "Titulo",
                        !empty($npf->titulo) ? $npf->titulo : null,
                        false
                    );
                    $PrimeiroNome = $this->dom->createElement("PrimeiroNome");
                    $p = $npf->primeironome;
                    $this->dom->addChild(
                        $PrimeiroNome,
                        "Tipo",
                        !empty($p->tipo) ? $p->tipo : null,
                        false
                    );
                    $this->dom->addChild(
                        $PrimeiroNome,
                        "Nome",
                        $p->nome,
                        true
                    );
                    $NomePF->appendChild($PrimeiroNome);
                    if (!empty($npf->meionome)) {
                        foreach ($npf->meionome as $p) {
                            $MeioNome = $this->dom->createElement("MeioNome");
                            $this->dom->addChild(
                                $MeioNome,
                                "Tipo",
                                !empty($p->tipo) ? $p->tipo : null,
                                false
                            );
                            $this->dom->addChild(
                                $MeioNome,
                                "Nome",
                                $p->nome,
                                true
                            );
                            $NomePF->appendChild($MeioNome);
                        }
                    }
                    if (!empty($npf->prefixonome)) {
                        $p = $npf->prefixonome;
                        $PrefixoNome = $this->dom->createElement("PrefixoNome");
                        $this->dom->addChild(
                            $PrefixoNome,
                            "Tipo",
                            !empty($p->tipo) ? $p->tipo : null,
                            false
                        );
                        $this->dom->addChild(
                            $PrefixoNome,
                            "Nome",
                            $p->nome,
                            true
                        );
                        $NomePF->appendChild($PrefixoNome);
                    }
                    $UltimoNome = $this->dom->createElement("UltimoNome");
                    $p = $npf->ultimonome;
                    $this->dom->addChild(
                        $UltimoNome,
                        "Tipo",
                        !empty($p->tipo) ? $p->tipo : null,
                        false
                    );
                    $this->dom->addChild(
                        $UltimoNome,
                        "Nome",
                        $p->nome,
                        true
                    );
                    $NomePF->appendChild($UltimoNome);
                    $this->dom->addChild(
                        $NomePF,
                        "IdGeracao",
                        !empty($npf->idgeracao) ? $npf->idgeracao : null,
                        false
                    );
                    $this->dom->addChild(
                        $NomePF,
                        "Sufixo",
                        !empty($npf->sufixo) ? $npf->sufixo : null,
                        false
                    );
                    $this->dom->addChild(
                        $NomePF,
                        "GenSufixo",
                        !empty($npf->gensufixo) ? $npf->gensufixo : null,
                        false
                    );
                    $NomeOutros->appendChild($NomePF);
                }
                if (!empty($no->nomepj)) {
                    $npj = $no->nomepj;
                    $NomePJ = $this->dom->createElement("NomePJ");
                    $this->dom->addChild(
                        $NomePJ,
                        "tpNome",
                        !empty($npj->tpnome) ? $npj->tpnome : null,
                        false
                    );
                    $this->dom->addChild(
                        $NomePJ,
                        "Nome",
                        $npj->nome,
                        true
                    );
                    $NomeOutros->appendChild($NomePJ);
                }
                $ideDeclarado->appendChild($NomeOutros);
            }
        }

        $this->dom->addChild(
            $ideDeclarado,
            "DataNasc",
            !empty($this->std->datanasc) ? $this->std->datanasc : null,
            false
        );

        if (!empty($this->std->infonascimento)) {
            $in = $this->std->infonascimento;
            $InfoNascimento = $this->dom->createElement("InfoNascimento");
            $this->dom->addChild(
                $InfoNascimento,
                "Municipio",
                !empty($in->municipio) ? $in->municipio : null,
                false
            );
            $this->dom->addChild(
                $InfoNascimento,
                "Bairro",
                !empty($in->bairro) ? $in->bairro : null,
                false
            );
            if (!empty($in->pais)) {
                $PaisNasc = $this->dom->createElement("PaisNasc");
                $this->dom->addChild(
                    $PaisNasc,
                    "Pais",
                    $in->pais,
                    false
                );
                $InfoNascimento->appendChild($PaisNasc);
            } elseif (!empty($in->antigonomepais)) {
                $PaisNasc = $this->dom->createElement("PaisNasc");
                $this->dom->addChild(
                    $PaisNasc,
                    "AntigoNomePais",
                    $in->antigonomepais,
                    false
                );
                $InfoNascimento->appendChild($PaisNasc);
            }
            $ideDeclarado->appendChild($InfoNascimento);
        }
        $this->dom->addChild(
            $ideDeclarado,
            "EnderecoLivre",
            !empty($this->std->enderecolivre) ? $this->std->enderecolivre : null,
            false
        );
        $this->dom->addChild(
            $ideDeclarado,
            "tpEndereco",
            !empty($this->std->tpendereco) ? $this->std->tpendereco : null,
            false
        );
        $PaisEndereco = $this->dom->createElement("PaisEndereco");
        $this->dom->addChild(
            $PaisEndereco,
            "Pais",
            $this->std->pais,
            true
        );
        $ideDeclarado->appendChild($PaisEndereco);

        if (!empty($this->std->enderecooutros)) {
            foreach ($this->std->enderecooutros as $eo) {
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
                        !empty($ee->enderecolivre) ? $ee->enderecolivre : null,
                        false
                    );
                    if (!empty($ee->endereco)) {
                        $end = $ee->endereco;
                        $Endereco = $this->dom->createElement("Endereco");
                        $this->dom->addChild(
                            $Endereco,
                            "Logradouro",
                            !empty($end->logradouro) ? $end->logradouro : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Numero",
                            !empty($end->numero) ? $end->numero : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Complemento",
                            !empty($end->complemento) ? $end->complemento : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Andar",
                            !empty($end->andar) ? $end->andar : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "Bairro",
                            !empty($end->bairro) ? $end->bairro : null,
                            false
                        );
                        $this->dom->addChild(
                            $Endereco,
                            "CaixaPostal",
                            !empty($end->caixapostal) ? $end->caixapostal : null,
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

                $ideDeclarado->appendChild($EnderecoOutros);
            }
        }
        if (!empty($this->std->paisresid)) {
            foreach ($this->std->paisresid as $pr) {
                $paisResid = $this->dom->createElement("paisResid");
                $this->dom->addChild(
                    $paisResid,
                    "Pais",
                    $pr->pais,
                    true
                );
                $ideDeclarado->appendChild($paisResid);
            }
        }
        if (!empty($this->std->paisnacionalidade)) {
            foreach ($this->std->paisnacionalidade as $pr) {
                $PaisNacionalidade = $this->dom->createElement("PaisNacionalidade");
                $this->dom->addChild(
                    $PaisNacionalidade,
                    "Pais",
                    $pr->pais,
                    true
                );
                $ideDeclarado->appendChild($PaisNacionalidade);
            }
        }
        if (!empty($this->std->proprietarios)) {
            foreach ($this->std->proprietarios as $p) {
                $Proprietarios = $this->dom->createElement("Proprietarios");
                $this->dom->addChild(
                    $Proprietarios,
                    "tpNI",
                    $p->tpni,
                    true
                );
                $this->dom->addChild(
                    $Proprietarios,
                    "NIProprietario",
                    $p->niproprietario,
                    true
                );
                $this->dom->addChild(
                    $Proprietarios,
                    "tpProprietario",
                    !empty($p->tpproprietario) ? $p->tpproprietario : null,
                    false
                );
                if (!empty($p->nif)) {
                    foreach ($p->nif as $n) {
                        $NIF = $this->dom->createElement("NIF");
                        $this->dom->addChild(
                            $NIF,
                            "NumeroNIF",
                            $n->numeronif,
                            true
                        );
                        $this->dom->addChild(
                            $NIF,
                            "PaisEmissaoNIF",
                            $n->paisemissaonif,
                            true
                        );
                        $Proprietarios->appendChild($NIF);
                    }
                }
                $this->dom->addChild(
                    $Proprietarios,
                    "Nome",
                    $p->nome,
                    true
                );
                $this->dom->addChild(
                    $Proprietarios,
                    "tpNome",
                    !empty($p->tpnome) ? $p->tpnome : null,
                    false
                );

                if (!empty($p->nomeoutros)) {
                    foreach ($p->nomeoutros as $no) {
                        $NomeOutros = $this->dom->createElement("NomeOutros");
                        $npf = $no->nomepf;
                        $NomePF = $this->dom->createElement("NomePF");
                        $this->dom->addChild(
                            $NomePF,
                            "tpNome",
                            !empty($npf->tpnome) ? $npf->tpnome : null,
                            false
                        );
                        $this->dom->addChild(
                            $NomePF,
                            "PrecTitulo",
                            !empty($npf->prectitulo) ? $npf->prectitulo : null,
                            false
                        );
                        $this->dom->addChild(
                            $NomePF,
                            "Titulo",
                            !empty($npf->titulo) ? $npf->titulo : null,
                            false
                        );
                        $PrimeiroNome = $this->dom->createElement("PrimeiroNome");
                            $pn = $npf->primeironome;
                            $this->dom->addChild(
                                $PrimeiroNome,
                                "Tipo",
                                !empty($pn->tipo) ? $pn->tipo : null,
                                false
                            );
                            $this->dom->addChild(
                                $PrimeiroNome,
                                "Nome",
                                $pn->nome,
                                true
                            );
                            $NomePF->appendChild($PrimeiroNome);
                        if (!empty($npf->meionome)) {
                            foreach ($npf->meionome as $pn) {
                                $MeioNome = $this->dom->createElement("MeioNome");
                                $this->dom->addChild(
                                    $MeioNome,
                                    "Tipo",
                                    !empty($pn->tipo) ? $pn->tipo : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $MeioNome,
                                    "Nome",
                                    $pn->nome,
                                    true
                                );
                                $NomePF->appendChild($MeioNome);
                            }
                        }
                        if (!empty($npf->prefixonome)) {
                            $pn = $npf->prefixonome;
                            $PrefixoNome = $this->dom->createElement("PrefixoNome");
                            $this->dom->addChild(
                                $PrefixoNome,
                                "Tipo",
                                !empty($pn->tipo) ? $pn->tipo : null,
                                false
                            );
                            $this->dom->addChild(
                                $PrefixoNome,
                                "Nome",
                                $pn->nome,
                                true
                            );
                            $NomePF->appendChild($PrefixoNome);
                        }
                        $UltimoNome = $this->dom->createElement("UltimoNome");
                        $pn = $npf->ultimonome;
                        $this->dom->addChild(
                            $UltimoNome,
                            "Tipo",
                            !empty($pn->tipo) ? $pn->tipo : null,
                            false
                        );
                        $this->dom->addChild(
                            $UltimoNome,
                            "Nome",
                            $pn->nome,
                            true
                        );
                        $NomePF->appendChild($UltimoNome);
                        $NomeOutros->appendChild($NomePF);
                        $Proprietarios->appendChild($NomeOutros);
                    }
                }
                $this->dom->addChild(
                    $Proprietarios,
                    "EnderecoLivre",
                    $p->enderecolivre,
                    true
                );
                $this->dom->addChild(
                    $Proprietarios,
                    "tpEndereco",
                    !empty($p->tpendereco) ? $p->tpendereco : null,
                    false
                );
                $PaisEndereco = $this->dom->createElement("PaisEndereco");
                $this->dom->addChild(
                    $PaisEndereco,
                    "Pais",
                    $p->pais,
                    true
                );
                $Proprietarios->appendChild($PaisEndereco);

                if (!empty($p->enderecooutros)) {
                    foreach ($p->enderecooutros as $eo) {
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
                                !empty($ee->enderecolivre) ? $ee->enderecolivre : null,
                                false
                            );
                            if (!empty($ee->endereco)) {
                                $end = $ee->endereco;
                                $Endereco = $this->dom->createElement("Endereco");
                                $this->dom->addChild(
                                    $Endereco,
                                    "Logradouro",
                                    !empty($end->logradouro) ? $end->logradouro : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $Endereco,
                                    "Numero",
                                    !empty($end->numero) ? $end->numero : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $Endereco,
                                    "Complemento",
                                    !empty($end->complemento) ? $end->complemento : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $Endereco,
                                    "Andar",
                                    !empty($end->andar) ? $end->andar : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $Endereco,
                                    "Bairro",
                                    !empty($end->bairro) ? $end->bairro : null,
                                    false
                                );
                                $this->dom->addChild(
                                    $Endereco,
                                    "CaixaPostal",
                                    !empty($end->caixapostal) ? $end->caixapostal : null,
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
                        $Proprietarios->appendChild($EnderecoOutros);
                    }
                }

                if (!empty($p->paisresid)) {
                    foreach ($p->paisresid as $pr) {
                        $paisResid = $this->dom->createElement("paisResid");
                        $this->dom->addChild(
                            $paisResid,
                            "Pais",
                            $pr->pais,
                            true
                        );
                        $Proprietarios->appendChild($paisResid);
                    }
                }
                if (!empty($p->paisnacionalidade)) {
                    foreach ($p->paisnacionalidade as $pr) {
                        $PaisNacionalidade = $this->dom->createElement("PaisNacionalidade");
                        $this->dom->addChild(
                            $PaisNacionalidade,
                            "Pais",
                            $pr->pais,
                            true
                        );
                        $Proprietarios->appendChild($PaisNacionalidade);
                    }
                }

                if (!empty($p->infonascimento)) {
                    $in = $p->infonascimento;
                    $InfoNascimento = $this->dom->createElement("InfoNascimento");
                    $this->dom->addChild(
                        $InfoNascimento,
                        "Municipio",
                        !empty($in->municipio) ? $in->municipio : null,
                        false
                    );
                    $this->dom->addChild(
                        $InfoNascimento,
                        "Bairro",
                        !empty($in->bairro) ? $in->bairro : null,
                        false
                    );
                    if (!empty($in->pais)) {
                        $PaisNasc = $this->dom->createElement("PaisNasc");
                        $this->dom->addChild(
                            $PaisNasc,
                            "Pais",
                            $in->pais,
                            false
                        );
                        $InfoNascimento->appendChild($PaisNasc);
                    } elseif (!empty($in->antigonomepais)) {
                        $PaisNasc = $this->dom->createElement("PaisNasc");
                        $this->dom->addChild(
                            $PaisNasc,
                            "AntigoNomePais",
                            $in->antigonomepais,
                            false
                        );
                        $InfoNascimento->appendChild($PaisNasc);
                    }
                    $Proprietarios->appendChild($InfoNascimento);
                }

                foreach ($p->reportavel as $r) {
                    $Reportavel = $this->dom->createElement("Reportavel");
                    $this->dom->addChild(
                        $Reportavel,
                        "Pais",
                        $r->pais,
                        true
                    );
                    $Proprietarios->appendChild($Reportavel);
                }
                $ideDeclarado->appendChild($Proprietarios);
            }
        }
        $this->node->appendChild($ideDeclarado);

        $mesCaixa = $this->dom->createElement("mesCaixa");
        $this->dom->addChild(
            $mesCaixa,
            "anoMesCaixa",
            $this->std->anomescaixa,
            true
        );
        $movOpFin = $this->dom->createElement("movOpFin");
        if (!empty($this->std->conta)) {
            foreach ($this->std->conta as $c) {
                $Conta = $this->dom->createElement("Conta");
                if (!empty($c->medjudic)) {
                    foreach ($c->medjudic as $j) {
                        $MedJudic = $this->dom->createElement("MedJudic");
                        $this->dom->addChild(
                            $MedJudic,
                            "NumProcJud",
                            $j->numprocjud,
                            true
                        );
                        $this->dom->addChild(
                            $MedJudic,
                            "Vara",
                            $j->vara,
                            true
                        );
                        $this->dom->addChild(
                            $MedJudic,
                            "SecJud",
                            $j->secjud,
                            true
                        );
                        $this->dom->addChild(
                            $MedJudic,
                            "SubSecJud",
                            $j->subsecjud,
                            true
                        );
                        $this->dom->addChild(
                            $MedJudic,
                            "dtConcessao",
                            $j->dtconcessao,
                            true
                        );
                        $this->dom->addChild(
                            $MedJudic,
                            "dtCassacao",
                            !empty($j->dtcassacao) ? $j->dtcassacao : null,
                            false
                        );
                        $Conta->appendChild($MedJudic);
                    }
                }

                if (!empty($c->infoconta)) {
                    $ic = $c->infoconta;
                    $infoConta = $this->dom->createElement("infoConta");
                    foreach ($ic->reportavel as $r) {
                        $Reportavel = $this->dom->createElement("Reportavel");
                        $this->dom->addChild(
                            $Reportavel,
                            "Pais",
                            $r->pais,
                            true
                        );
                        $infoConta->appendChild($Reportavel);
                    }
                    $this->dom->addChild(
                        $infoConta,
                        "tpConta",
                        $ic->tpconta,
                        true
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "subTpConta",
                        $ic->subtpconta,
                        true
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "tpNumConta",
                        $ic->tpnumconta,
                        true
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "numConta",
                        $ic->numconta,
                        true
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "tpRelacaoDeclarado",
                        $ic->tprelacaodeclarado,
                        true
                    );

                    if (!empty($ic->intermediario)) {
                        $i = $ic->intermediario;
                        $Intermediario = $this->dom->createElement("Intermediario");
                        $this->dom->addChild(
                            $Intermediario,
                            "GIIN",
                            !empty($i->giin) ? $i->giin : null,
                            false
                        );
                        $this->dom->addChild(
                            $Intermediario,
                            "tpNI",
                            !empty($i->tpni) ? $i->tpni : null,
                            false
                        );
                        $this->dom->addChild(
                            $Intermediario,
                            "NIIntermediario",
                            !empty($i->niintermediario) ? $i->niintermediario : null,
                            false
                        );
                        $infoConta->appendChild($Intermediario);
                    }
                    $this->dom->addChild(
                        $infoConta,
                        "NoTitulares",
                        !empty($ic->notitulares) ? $ic->notitulares : null,
                        false
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "dtEncerramentoConta",
                        !empty($ic->dtencerramentoconta) ? $ic->dtencerramentoconta : null,
                        false
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "IndInatividade",
                        !empty($ic->indinatividade) ? $ic->indinatividade : null,
                        false
                    );
                    $this->dom->addChild(
                        $infoConta,
                        "IndNDoc",
                        !empty($ic->indndoc) ? $ic->indndoc : null,
                        false
                    );
                    if (!empty($ic->fundo)) {
                        $f = $ic->fundo;
                        $Fundo = $this->dom->createElement("Fundo");
                        $this->dom->addChild(
                            $Fundo,
                            "GIIN",
                            !empty($f->giin) ? $f->giin : null,
                            false
                        );
                        $this->dom->addChild(
                            $Fundo,
                            "CNPJ",
                            $f->cnpj,
                            true
                        );
                        $infoConta->appendChild($Fundo);
                    }

                    $BalancoConta = $this->dom->createElement("BalancoConta");
                    $this->dom->addChild(
                        $BalancoConta,
                        "totCreditos",
                        number_format($ic->totcreditos, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $BalancoConta,
                        "totDebitos",
                        number_format($ic->totdebitos, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $BalancoConta,
                        "totCreditosMesmaTitularidade",
                        number_format($ic->totcreditosmesmatitularidade, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $BalancoConta,
                        "totDebitosMesmaTitularidade",
                        number_format($ic->totdebitosmesmatitularidade, 2, ',', ''),
                        true
                    );
                    $this->dom->addChild(
                        $BalancoConta,
                        "vlrUltDia",
                        isset($ic->vlrultdia) ? number_format($ic->vlrultdia, 2, ',', '') : null,
                        false
                    );
                    $infoConta->appendChild($BalancoConta);

                    foreach ($ic->pgtosacum as $pg) {
                        $PgtosAcum = $this->dom->createElement("PgtosAcum");
                        $this->dom->addChild(
                            $PgtosAcum,
                            "tpPgto",
                            $pg->tppgto,
                            true
                        );
                        $this->dom->addChild(
                            $PgtosAcum,
                            "totPgtosAcum",
                            number_format($pg->totpgtosacum, 2, ',', ''),
                            true
                        );
                        $infoConta->appendChild($PgtosAcum);
                    }
                    $Conta->appendChild($infoConta);
                }
                $movOpFin->appendChild($Conta);
            }
        }

        if (!empty($this->std->cambio)) {
            $c = $this->std->cambio;
            $Cambio = $this->dom->createElement("Cambio");
            if (!empty($c->medjudic)) {
                foreach ($c->medjudic as $j) {
                    $MedJudic = $this->dom->createElement("MedJudic");
                    $this->dom->addChild(
                        $MedJudic,
                        "NumProcJud",
                        $j->numprocjud,
                        true
                    );
                    $this->dom->addChild(
                        $MedJudic,
                        "Vara",
                        $j->vara,
                        true
                    );
                    $this->dom->addChild(
                        $MedJudic,
                        "SecJud",
                        $j->secjud,
                        true
                    );
                    $this->dom->addChild(
                        $MedJudic,
                        "SubSecJud",
                        $j->subsecjud,
                        true
                    );
                    $this->dom->addChild(
                        $MedJudic,
                        "dtConcessao",
                        $j->dtconcessao,
                        true
                    );
                    $this->dom->addChild(
                        $MedJudic,
                        "dtCassacao",
                        !empty($j->dtcassacao) ? $j->dtcassacao : null,
                        false
                    );
                    $Cambio->appendChild($MedJudic);
                }
                $this->dom->addChild(
                    $Cambio,
                    "totCompras",
                    number_format($c->totcompras, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $Cambio,
                    "totVendas",
                    number_format($c->totvendas, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $Cambio,
                    "totTransferencias",
                    number_format($c->tottransferencias, 2, ',', ''),
                    true
                );
            }
            $movOpFin->appendChild($Cambio);
        }

        $mesCaixa->appendChild($movOpFin);
        $this->node->appendChild($mesCaixa);

        //finalização do xml
        $this->eFinanceira->appendChild($this->node);
        //$this->xml = $this->dom->saveXML($this->eFinanceira);
        $this->sign($this->evtTag);
    }
}
