<?php

namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de Movimento
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\Movimento
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\MovProprietario;

class Movimento extends MovProprietario
{
    /**
     * Conjunto de movimentos
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aConta = array();
    /**
     * Objeto Dom tag mesCaixa
     * @var Dom
     */
    protected $mesCaixa;
    /**
     * Conjunto de Medidas Judiciais de Contas
     * para cada conta
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aContaMedJudic = array();
    /**
     * Conjunto de Paises Reportáveis
     * para cada conta
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aContaRep = array();
    /**
     * Conjunto de Intermediarios
     * para cada conta
     * Array de objetos Dom
     * 
     * @var array
     */
    protected $aContaIntermediario = array();
    /**
     * Conjunto de Fundos
     * para cada conta
     * Array de objetos Dom
     * 
     * @var array
     */
    protected $aContaFundo = array();
    /**
     * Conjunto de Balanços
     * para cada conta
     * Array de objetos Dom
     * 
     * @var array
     */
    protected $aContaBalanco = array();
    /**
     * Conjunto de Pagamentos acumulados
     * para cada conta
     * Array de objetos Dom
     * 
     * @var array
     */
    protected $aContaPgtosAcum = array();
    
    /**
     * Objeto Dom tag Cambio
     * @var Dom
     */
    protected $cambio;
    /**
     * Conjunto de Medidas Judiciais de Cambio de Contas
     * para cada conta
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aCambioMedJudic = array();   
    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtMovOpFin';
    
    /**
     * Premonta os objetos dessa classe 
     * @return none
     */
    protected function premonta()
    {
        parent::premonta();
        if (empty($this->mesCaixa)) {
            return;
        }
        $movOpFin = $this->dom->createElement("movOpFin");
        //listar os numeros das contas registradas
        $aCT = array_keys($this->aConta);
        foreach ($aCT as $num) {
            //verificar se existem medidas judiciais para a conta
            if (array_key_exists($num, $this->aContaMedJudic)) {
                foreach($this->aContaMedJudic[$num] as $med) {
                    $this->dom->appChildBefore($this->aConta[$num], $med, "tpConta");
                }
            }
            //verificar se existem paises reportáveis na conta
            if (array_key_exists($num, $this->aContaRep)) {
                foreach($this->aContaRep[$num] as $rep) {
                    $this->dom->appChildBefore($this->aConta[$num], $rep, "tpConta");
                }    
            }
            //verificar se existem intermediarios
            if (array_key_exists($num, $this->aContaIntermediario)) {
                $notit = $this->aConta[$num]->getElementsByTagName('NoTitulares')->item(0);
                if (!empty($notit)) {
                    $this->dom->appChildBefore($this->aConta[$num], $this->aContaIntermediario[$num], 'NoTitulares');
                } else {
                    $notit = $this->aConta[$num]->getElementsByTagName('dtEncerramentoContas')->item(0);    
                    if (! empty($notit)) {
                        $this->dom->appChildBefore($this->aConta[$num], $this->aContaIntermediario[$num], 'dtEncerramentoContas');
                    } else {
                        $this->dom->appChild($this->aConta[$num], $this->aContaIntermediario[$num]);
                    }
                }
            }
            //verificar se existem Fundos de insvestimento na conta
            if (array_key_exists($num, $this->aContaFundo)) {
                $this->dom->appChild($this->aConta[$num], $this->aContaFundo[$num]);
            }
            //verificar se existem balancos na conta
            if (array_key_exists($num, $this->aContaBalanco)) {
                $this->dom->appChild($this->aConta[$num], $this->aContaBalanco[$num]);
            }
            //verificar se existem pagamentos acumulados
            if (array_key_exists($num, $this->aContaPgtosAcum)) {
                foreach($this->aContaPgtosAcum[$num] as $pag) {
                    $this->dom->appChild($this->aConta[$num], $pag);
                }
            }
            $conta = $this->dom->createElement("Conta");
            $this->dom->appChild($conta, $this->aConta[$num]);
            $this->dom->appChild($movOpFin, $conta);
            $conta = null;
        }
        //carrega dados de cambio
        if (!empty($this->cambio)) {
            //insere medidas judiciais se existirem
            foreach($this->aCambioMedJudic as $med) {
                $this->dom->appChildBefore($this->cambio, $med, 'totCompras');
            }
            $this->dom->appChild($movOpFin, $this->cambio);
        }
        $this->dom->appChild($this->mesCaixa, $movOpFin);
        $this->dom->appChild($this->evt, $this->mesCaixa);
    }
    
    /**
     * Cria o conjunto de tags Conta MedJudic
     * Podem existir ZERO ou mais desse tipo
     *
     * @param string $numConta    Obrigatorio
     * @param string $numProcJud  Obrigatorio
     * @param string $vara        Obrigatorio
     * @param string $secJud      Obrigatorio
     * @param string $subSecJud   Obrigatorio
     * @param string $dtConcessao Obrigatorio
     * @param string $dtCassacao  caso não exista deixe uma string vazia
     * @return Dom tag MedJudic
     */
    public function contaMedJudic(
        $numConta,
        $numProcJud,
        $vara,
        $secJud,
        $subSecJud,
        $dtConcessao,
        $dtCassacao
    ) {
        $medJudic = $this->zMedJudic($numProcJud, $vara, $secJud, $subSecJud, $dtConcessao, $dtCassacao);
        $this->aContaMedJudic[$numConta][] = $medJudic;
        return $medJudic;
    }
    
    /**
     * Cria a tag Conta Reportavel
     * Deve existir pelo menos um registro desse tipo
     *
     * @param string $numConta
     * @param string $pais
     * @return Dom tag Reportavel
     */
    public function contaReportavel($numConta, $pais)
    {
        $reportavel = $this->zPais($pais, "Reportavel");
        $this->aContaRep[$numConta][] = $reportavel;
        return $reportavel;
    }
    
    /**
     * Cria a tag mesCaixa
     *
     * @param string $anomes
     * @return Dom tag mesCaixa
     */
    public function movAnoMes($anomes)
    {
        $identificador = 'tag mesCaixa ';
        $mesCaixa = $this->dom->createElement("mesCaixa");
        $this->dom->addChild(
            $mesCaixa,
            "anoMesCaixa",
            $anomes,
            true,
            $identificador . "Mês caixa que está sendo reportado "
        );
        $this->mesCaixa = $mesCaixa;
        return $mesCaixa;
    }
    
    /**
     * Cria as tags conta intermediário
     *
     * @param string $numConta
     * @param string $giin
     * @param string $tpNI
     * @param string $nIIntermediario
     * @return Dom tag conta intermediario
     */
    public function contaIntermediario($numConta, $giin, $tpNI, $nIIntermediario)
    {
        $intermediario = $this->dom->createElement("intermediario");
        $this->dom->addChild(
            $intermediario,
            "GIIN",
            $giin,
            true,
            "GIIN (Global Intermediary Identification Number) "
        );
        $this->dom->addChild(
            $intermediario,
            "tpNI",
            $tpNI,
            true,
            "Tipo de NI "
        );
        $this->dom->addChild(
            $intermediario,
            "NIIntermediario",
            $nIIntermediario,
            false,
            "NI "
        );
        $this->aContaIntermediario[$numConta] = $intermediario;
        return $intermediario;
    }
    
    /**
     * Cria a tag conta fundo
     *
     * @param string $numConta
     * @param string $giin
     * @param string $cnpj
     * @return Dom tag fundo de investimento da conta
     */
    public function contaFundo($numConta, $giin, $cnpj)
    {
        $fundo = $this->dom->createElement("Fundo");
        $this->dom->addChild(
            $fundo,
            "GIIN",
            $giin,
            true,
            "GIIN do fundo "
        );
        $this->dom->addChild(
            $fundo,
            "CNPJ",
            $cnpj,
            true,
            "CNPJ do fundo "
        );
        $this->aContaFundo[$numConta] = $fundo;
        return $fundo;
    }
    
    /**
     * Cria o conjunto de contas
     * 
     * @param string $numConta
     * @param string $tpConta
     * @param string $subTpConta
     * @param string $tpNumConta
     * @param string $tpRelacaoDeclarado
     * @param string $noTitulares
     * @param string $dtEncerramentoConta se não estiver encerrada seixe uma string vazia
     * @return Dom
     */
    public function conta(
        $numConta,
        $tpConta,
        $subTpConta,
        $tpNumConta,
        $tpRelacaoDeclarado,
        $noTitulares,
        $dtEncerramentoConta
    ) {
        $identificador = 'tag conta';
        $infoConta = $this->dom->createElement("infoConta");
        $this->dom->addChild(
            $infoConta,
            "tpConta",
            $tpConta,
            true,
            $identificador . "tipo de conta "
        );
        $this->dom->addChild(
            $infoConta,
            "subTpConta",
            $subTpConta,
            true,
            $identificador . "Subtipo de conta "
        );
        $this->dom->addChild(
            $infoConta,
            "tpNumConta",
            $tpNumConta,
            true,
            $identificador . "tipo de numero de conta "
        );
        $this->dom->addChild(
            $infoConta,
            "numConta",
            $numConta,
            true,
            $identificador . "numero de conta "
        );
        $this->dom->addChild(
            $infoConta,
            "tpRelacaoDeclarado",
            $tpRelacaoDeclarado,
            true,
            $identificador . "Tipo de relação do declarado "
        );
        $this->dom->addChild(
            $infoConta,
            "NoTitulares",
            $noTitulares,
            false,
            $identificador . "Numero de Titulares "
        );
        $this->dom->addChild(
            $infoConta,
            "dtEncerramentoConta",
            $dtEncerramentoConta,
            false,
            $identificador . "Data de Encerramento da Conta "
        );
        $this->aConta[$numConta] = $infoConta;
        return $infoConta;
    }
    
    /**
     * Cria a tag contaBalanco
     *
     * @param string $numConta
     * @param string $totCreditos
     * @param string $totDebitos
     * @param string $totCreditosMesmaTitularidade
     * @param string $totDebitosMesmaTitularidade
     * @param string $vlrUltDia
     * @return Dom tag balanco
     */
    public function contaBalanco(
        $numConta,
        $totCreditos,
        $totDebitos,
        $totCreditosMesmaTitularidade,
        $totDebitosMesmaTitularidade,
        $vlrUltDia
    ) {
        $identificador = 'tag contaBalanco';
        $balanco = $this->dom->createElement("BalancoConta");
        $this->dom->addChild(
            $balanco,
            "totCreditos",
            $totCreditos,
            true,
            $identificador . "totais de creditos"
        );
        $this->dom->addChild(
            $balanco,
            "totDebitos",
            $totDebitos,
            true,
            $identificador . "totais de debitos"
        );
        $this->dom->addChild(
            $balanco,
            "totCreditosMesmaTitularidade",
            $totCreditosMesmaTitularidade,
            true,
            $identificador . "totais de creditos de mesma titularidade"
        );
        $this->dom->addChild(
            $balanco,
            "totDebitosMesmaTitularidade",
            $totDebitosMesmaTitularidade,
            true,
            $identificador . "totais de debitos de mesma titularidade"
        );
        $this->dom->addChild(
            $balanco,
            "vlrUltDia",
            $vlrUltDia,
            true,
            $identificador . "Saldo no último dia do mês ou do momento anterior ao encerramento da conta"
        );
        $this->aContaBalanco[$numConta] = $balanco;
        return $balanco;
    }
    
    /**
     * Cria a tag PgtosAcum
     *
     * @param string $numConta
     * @param array $tpPgto
     * @param string$totPgtosAcum
     * @return Dom tag PgtosAcum
     */
    public function contaPgtosAcum($numConta, $tpPgto, $totPgtosAcum)
    {
        $identificador = 'tag PgtosAcum';
        $pgtos = $this->dom->createElement("PgtosAcum");
        foreach ($tpPgto as $tpp) {
            $this->dom->addChild(
                $pgtos,
                "tpPgto",
                $tpp,
                true,
                $identificador . "tipo de pagamento"
            );
        }
        $this->dom->addChild(
            $pgtos,
            "totPgtosAcum",
            $totPgtosAcum,
            true,
            $identificador . "Total de pagamentos acumulados"
        );
        $this->aContaPgtosAcum[$numConta][] = $pgtos;
        return $pgtos;
    }
    
    /**
     * Cria a tag Cambio
     * 
     * @param string $totCompras
     * @param string $totVendas
     * @param string $totTransferencias
     * @return Dom
     */
    public function cambio(
        $totCompras,
        $totVendas,
        $totTransferencias
    ) {
        $identificador = 'tag Cambio';
        $cambio = $this->dom->createElement("Cambio");
        $this->dom->addChild(
            $cambio,
            "totCompras",
            $totCompras,
            true,
            $identificador . "Total de compras"
        );
        $this->dom->addChild(
            $cambio,
            "totVendas",
            $totVendas,
            true,
            $identificador . "Total de vendas"
        );
        $this->dom->addChild(
            $cambio,
            "totTransferencias",
            $totTransferencias,
            true,
            $identificador . "Total de transferencias"
        );
        $this->cambio = $cambio;
        return $cambio;
    }
    
     /**
     * Cria o conjunto de tags Cambio MedJudic
     * Podem existir ZERO ou mais desse tipo
     *
     * @param string $numProcJud  Obrigatorio
     * @param string $vara        Obrigatorio
     * @param string $secJud      Obrigatorio
     * @param string $subSecJud   Obrigatorio
     * @param string $dtConcessao Obrigatorio
     * @param string $dtCassacao  caso não exista deixe uma string vazia
     * @return Dom tag MedJudic
     */
    public function cambioMedJudic(
        $numProcJud,
        $vara,
        $secJud,
        $subSecJud,
        $dtConcessao,
        $dtCassacao
    ) {
        $medJudic = $this->zMedJudic($numProcJud, $vara, $secJud, $subSecJud, $dtConcessao, $dtCassacao);
        $this->aCambioMedJudic[] = $medJudic;
        return $medJudic;
    }
}
