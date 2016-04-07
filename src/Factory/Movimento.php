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

use NFePHP\eFinanc\Factory\Factory;

class Movimento extends Factory
{
    /**
     * Conjunto de proprietários
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aProp;

    /**
     * Conjunto de movimentos
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aMov;
    /**
     * Conjunto de Medidas Judiciais de Contas
     * para cada conta
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aContaMedJudic = array();
    /**
     * Conjunto de Medidas Judiciais de Cambio de Contas
     * para cada conta
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aCambioMedJudic = array();
    
    /**
     * Conjunto de Paises Reportáveis
     * para cada anomes e conta
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aMovRep = array();
    /**
     * Conjunto de identificadores NIIF do declarado
     * Array de objetos Dom
     *
     * @var array
     */
    protected $aDeclaradoNIF = array();
    
    protected $aDeclaradoPaisResid = array();
    
    protected $aDeclaradoPaisNac = array();
    
    protected $aTpdeclarado = array();
    
    protected $aProprietarioNIF = array();

    protected $aProprietarioPaisResid = array();
    
    protected $aProprietarioPaisNac = array();

    protected $aProprietarioReportavel = array();

    /**
     * Objeto Dom::class Tag ideDeclarado
     * @var Dom
     */
    protected $ideDeclarado;

    /**
     * estabelece qual a tag será assinada
     * @var string
     */
    protected $signTag = 'evtMovOpFin';

    protected function premonta()
    {
        return;
    }
    
    /**
     * Cria as tags NIF do declarado
     * podem existir ZERO ou mais
     *
     * @param string $numeroNIF
     * @param string $paisEmissao
     * @return Dom tag NIF
     */
    public function declaradoNIF($numeroNIF, $paisEmissao)
    {
        $nif = $this->zNIF($numeroNIF, $paisEmissao);
        $this->aDeclaradoNIF[] = $nif;
        return $nif;
    }

    /**
     * Cria as tags NIF do proprietario
     * podem existir ZERO ou mais
     *
     * @param string $nIProprietario identificação do proprietário
     * @param string $numeroNIF
     * @param string $paisEmissao
     * @return Dom tag NIF
     */
    public function proprietarioNIF($nIProprietario, $numeroNIF, $paisEmissao)
    {
        $nif = $this->zNIF($numeroNIF, $paisEmissao);
        $this->aProprietarioNIF[$nIProprietario][] = $nif;
        return $nif;
    }
    
    /**
     * Crias as tags PaisResid do declarado
     * podem existir ZERO ou mais
     *
     * @param string $pais
     * @return Dom tag PaisResid
     */
    public function declaradoPaisResid($pais)
    {
        $tpais = $this->zPais($pais, 'PaisResid');
        $this->aPaisResidDeclarado[] = $tpais;
        return $tpais;
    }
    /**
     * Crias as tags PaisNacionalidade do declarado
     * podem existir ZERO ou mais
     *
     * @param string $pais
     * @return Dom tag PaisResid
     */
    public function declaradoPaisNac($pais)
    {
        $tpais = $this->zPais($pais, 'PaisNacionalidade');
        $this->aPaisNacDeclarado[] = $tpais;
        return $tpais;
    }
    
    /**
     * Crias as tags PaisResid do declarado
     * podem existir ZERO ou mais
     *
     * @param string $nIProprietario identificação do proprietário
     * @param string $pais
     * @return Dom tag PaisResid
     */
    public function proprietarioPaisResid($nIProprietario, $pais)
    {
        $tpais = $this->zPais($pais, 'PaisResid');
        $this->aPaisResidProprietario[$nIProprietario][] = $tpais;
        return $tpais;
    }
    /**
     * Crias as tags PaisNacionalidade do declarado
     * podem existir ZERO ou mais
     *
     * @param string $nIProprietario identificação do proprietário
     * @param string $pais
     * @return Dom tag PaisResid
     */
    public function proprietarioPaisNac($nIProprietario, $pais)
    {
        $tpais = $this->zPais($pais, 'PaisNacionalidade');
        $this->aPaisNacProprietario[$nIProprietario][] = $tpais;
        return $tpais;
    }
    
    /**
     * Cria as tags Pais
     * podem existir ZERO ou mais
     *
     * @param string $pais
     * @param string $tag  PaisNacionalidade ou PaisResid
     * @return Dom tag PaisNacionalidade ou PaisResid
     */
    protected function zPais($pais, $tag)
    {
        $paisNac = $this->dom->createElement($tag);
        $this->dom->addChild(
            $paisNac,
            "Pais",
            $pais,
            true,
            $identificador . "Pais $tag do Declarado"
        );
        return $paisNac;
    }
    
    /**
     * Cria array com os tipos de declarado
     * podem ser ZERO ou mais
     *
     * @param string $tpDeclarado
     * @return string
     */
    public function declaradoTipo($tpDeclarado)
    {
        $this->aTpdeclarado[] = $tpDeclarado;
        return $tpDeclarado;
    }
    
    /**
     * Cria a tag ideDeclarado
     *
     * @param string $tpNI          Obrigatorio
     * @param string $nIDeclarado   Obrigatorio
     * @param string $nomeDeclarado Obrigatorio
     * @param string $dataNasc      Obrigatorio
     * @param string $enderecoLivre se não exisitr deixar uma string vazia
     * @param string $pais          Obrigatorio
     * @return Dom
     */
    public function declarado(
        $tpNI,
        $tpDeclarado,
        $nIDeclarado,
        $nomeDeclarado,
        $dataNasc,
        $enderecoLivre,
        $pais
    ) {
        $identificador = 'tag ideDeclarado ';
        $this->ideDeclarado = $this->dom->createElement("ideDeclarado");
        $this->dom->addChild(
            $this->ideDeclarado,
            "tpNI",
            $tpNI,
            true,
            $identificador . "tipo de NI "
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "NIDeclarado",
            $nIDeclarado,
            true,
            $identificador . "NI do declarado "
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "NomeDeclarado",
            $nomeDeclarado,
            true,
            $identificador . "Nome do Declarado"
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "DataNasc",
            $dataNasc,
            false,
            $identificador . "Nome do Declarado"
        );
        $this->dom->addChild(
            $this->ideDeclarado,
            "EnderecoLivre",
            $enderecoLivre,
            false,
            $identificador . "Endereco Livre do Declarado"
        );
        $pais = $this->dom->createElement("PaisEndereco");
        $this->dom->addChild(
            $pais,
            "Pais",
            $pais,
            true,
            $identificador . "Pais Endereco do Declarado"
        );
        $this->dom->appChild($this->ideDeclarado, $pais);
        return $this->ideDeclarado;
    }
    
    /**
     * Cria o conjunto de paises reportaveis para o proprietario
     *
     * @param string $nIProprietario identificação do proprietário
     * @param string $pais
     * @return Dom tag Reportavel
     */
    public function proprietarioReportavel($nIProprietario, $pais)
    {
        $reportPais = $this->dom->createElement("Reportavel");
        $this->dom->addChild(
            $reportPais,
            "Pais",
            $pais,
            true,
            $identificador . "Pais Nacionalidade do Declarado"
        );
        $this->aReportavelProprietario[$nIProprietario][] = $reportPais;
        return $reportPais;
    }
    
    /**
     * Cria o conjunto de tag de proprietarios
     *
     * @param string $tpNI
     * @param string $nIProprietario
     * @param string $nome
     * @param string $dataNasc
     * @param string $endereco
     * @param string $pais
     * @return Dom tag proprietario
     */
    public function proprietario(
        $tpNI,
        $nIProprietario,
        $nome,
        $dataNasc,
        $endereco,
        $pais,
        $reportavel
    ) {
        $identificador = 'tag Proprietarios ';
        $proprietario = $this->dom->createElement("Proprietarios");
        $this->dom->addChild(
            $proprietario,
            "tpNI",
            $tpNI,
            true,
            $identificador . "tipo de NI "
        );
        $this->dom->addChild(
            $proprietario,
            "NIProprietario",
            $nIProprietario,
            true,
            $identificador . "NI do proprietário"
        );
        $this->dom->addChild(
            $proprietario,
            "Nome",
            $nome,
            true,
            $identificador . "Nome do Proprietário"
        );
        $this->dom->addChild(
            $proprietario,
            "EnderecoLivre",
            $enderecoLivre,
            false,
            $identificador . "Endereco Livre do Proprietario"
        );
        $pais = $this->dom->createElement("PaisEndereco");
        $this->dom->addChild(
            $pais,
            "Pais",
            $pais,
            true,
            $identificador . "Pais Endereco do Declarado"
        );
        $this->dom->appChild($proprietario, $pais);
        $this->dom->addChild(
            $proprietario,
            "DataNasc",
            $dataNasc,
            false,
            $identificador . "Nome do Proprietário"
        );
        $this->aProp[$nIProprietario] = $proprietario;
        return $proprietario;
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
        $this->aMovMed[$numConta] = $medJudic;
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
        $reportavel = $this->dom->createElement("Reportavel");
        $this->dom->addChild(
            $reportavel,
            "Pais",
            $pais,
            true,
            $identificador . "Pais  "
        );
        $this->aMovRep[$numConta] = $reportavel;
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
            $identificador . "GIIN (Global Intermediary Identification Number) "
        );
        $this->dom->addChild(
            $intermediario,
            "tpNI",
            $tpNI,
            true,
            $identificador . "Tipo de NI "
        );
        $this->dom->addChild(
            $intermediario,
            "NIIntermediario",
            $nIIntermediario,
            false,
            $identificador . "NI "
        );
        $this->aIntermediarioConta[$numConta][] = $intermediario;
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
            $identificador . "GIIN do fundo "
        );
        $this->dom->addChild(
            $fundo,
            "CNPJ",
            $cnpj,
            true,
            $identificador . "CNPJ do fundo "
        );
        $this->aFundoConta[$numConta][] = $fundo;
        return $fundo;
    }
    
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
                $identificador . "tipode pagamento"
            );
        }
        $this->dom->addChild(
            $pgtos,
            "totPgtosAcum",
            $totPgtosAcum,
            true,
            $identificador . "Total de pagamentos acumulados"
        );
        $this->aContaPgtosAcum[$numConta] = $pgtos;
        return $pgtos;
    }
    
    public function contaCambio($numConta, $totCompras, $totVendas, $totTransferencias)
    {
        
    }
    
     /**
     * Cria o conjunto de tags Conta Cambio MedJudic
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
    public function contaCambioMedJudic(
        $numConta,
        $numProcJud,
        $vara,
        $secJud,
        $subSecJud,
        $dtConcessao,
        $dtCassacao
    ) {
        $medJudic = $this->zMedJudic($numProcJud, $vara, $secJud, $subSecJud, $dtConcessao, $dtCassacao);
        $this->aCambioMedJudic[$numConta] = $medJudic;
        return $medJudic;
    }
    
    /**
     * Cria as tags NIF do declarado e proprietario
     * podem existir ZERO ou mais
     *
     * @param string $numeroNIF
     * @param string $paisEmissao
     * @return Dom tag NIF
     */
    protected function zNIF($numeroNIF, $paisEmissao)
    {
        $nif = $this->dom->createElement("NIF");
        $this->dom->addChild(
            $nif,
            "NumeroNIF",
            $numeroNIF,
            true,
            $identificador . "numero NIF "
        );
        $this->dom->addChild(
            $nif,
            "PaisEmissaoNIF",
            $paisEmissao,
            true,
            $identificador . "Pais de Emissao do NIF "
        );
        return $nif;
    }
    
    /**
     * Cria o conjunto de tags  MedJudic
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
    protected function zMedJudic(
        $numProcJud,
        $vara,
        $secJud,
        $subSecJud,
        $dtConcessao,
        $dtCassacao
    ) {
        $medJudic = $this->dom->createElement("MedJudic");
        $this->dom->addChild(
            $medJudic,
            "NumProcJud",
            $numProcJud,
            true,
            $identificador . "Número do Processo Judicial "
        );
        $this->dom->addChild(
            $medJudic,
            "Vara",
            $vara,
            true,
            $identificador . "Vara de Tramitação "
        );
        $this->dom->addChild(
            $medJudic,
            "SecJud",
            $secJud,
            true,
            $identificador . "Seção judiciária "
        );
        $this->dom->addChild(
            $medJudic,
            "SubSecJud",
            $subSecJud,
            true,
            $identificador . "SubSeção judiciária "
        );
        $this->dom->addChild(
            $medJudic,
            "dtConcessao",
            $dtConcessao,
            true,
            $identificador . "Data da Concessão "
        );
        $this->dom->addChild(
            $medJudic,
            "dtCassacao",
            $dtCassacao,
            false,
            $identificador . "Data da Data da Cassação "
        );
        return $medJudic;
    }
}
