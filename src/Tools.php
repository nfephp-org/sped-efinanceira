<?php

namespace NFePHP\eFinanc;

/**
 * Classe Tools, executa a comunicação com o webservice do e-Financeira
 *
 * @category   NFePHP
 * @package    NFePHP\eFinanc\Factory\Tools
 * @copyright  Copyright (c) 2016
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\Common\Base\BaseTools;

class Tools extends BaseTools
{
    
    public $tpAmb = 2;
    public $url = [
        1 => [
            ['recepcao'=>'https://efinanc.receita.fazenda.gov.br/WsEFinanceira/WsRecepcao.asmx'],
            ['consulta'=>'https://efinanc.receita.fazenda.gov.br/WsEFinanceira/WsConsulta.asmx']
        ],
        2 => [
            ['recepcao'=>'https://preprod-efinanc.receita.fazenda.gov.br/WsEFinanceira/WsRecepcao.asmx'],
            ['consulta'=>'https://preprod-efinanc.receita.fazenda.gov.br/WsEFinanceira/WsConsulta.asmx']
        ]
    ];
    
    protected $xmlns = "http://sped.fazenda.gov.br/";

   
    /**
     * Consulta informações cadastrais do Declarante
     *
     * @param string $cnpjDeclarante
     * @param array $aResp variável passada como referencia irá conter os retornos
     *                     em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesCadastrais($cnpjDeclarante, &$aResp)
    {
        
        $msg = "<ConsultarInformacoesCadastrais><cnpj>$cnpjDeclarante</cnpj></ConsultarInformacoesCadastrais>";
        return '';
    }
    
    /**
     * Consulta Lista EFinanceira
     *
     * @param string $cnpjDeclarante
     * @param string $sitInfo
     * @param string $dtInicio
     * @param string $dtFim
     * @param array $aResp variável passada como referencia irá conter os retornos
     *                     em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarListaEFinanceira($cnpjDeclarante, $sitInfo, $dtInicio, $dtFim, &$aResp)
    {
        $msg = "<ConsultarListaEFinanceira>"
            . "<cnpj>$cnpjDeclarante</cnpj>"
            . "<situacaoEFinanceira>$sitInfo</situacaoEFinanceira>"
            . "<dataInicio>$dtInicio</dataInicio>"
            . "<dataFim>$dtFim</dataFim>"
            . "</ConsultarListaEFinanceira>";
        return '';
    }
    
    /**
     * Consulta INformações de Movimento
     *
     * @param string $cnpjDeclarante
     * @param string $sitInfo
     * @param string $anomesIni
     * @param string $anomesFim
     * @param string $tpMov
     * @param string $tpNI
     * @param string $numNI
     * @param array $aResp variável passada como referencia irá conter os retornos
     *                     em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesMovimento(
        $cnpjDeclarante,
        $sitInfo,
        $anomesIni,
        $anomesFim,
        $tpMov,
        $tpNI,
        $numNI,
        &$aResp
    ) {
        $msg = "<ConsultarInformacoesMovimento>"
            . "<cnpj>$cnpjDeclarante</cnpj>"
            . "<situacaoInformacao>$sitInfo</situacaoInformacao>"
            . "<anoMesInicioVigencia>$anomesIni</anoMesInicioVigencia>"
            . "<anoMesTerminoVigencia>$anomesFim</anoMesTerminoVigencia>"
            . "<tipoMovimento>$tpMov</tipoMovimento>"
            . "<tipoIdentificacao>$tpNI</tipoIdentificacao>"
            . "<identificacao>$numNI</identificacao>"
            . "</ConsultarInformacoesMovimento>";
        return '';
    }
    
    /**
     * Consulta informações do Intermediário
     *
     * @param string $cnpjDeclarante
     * @param string $gIIN
     * @param string $tpNI
     * @param string $numNI
     * @param array $aResp variável passada como referencia irá conter os retornos
     *                     em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesIntermediario($cnpjDeclarante, $gIIN, $tpNI, $numNI, &$aResp)
    {
        $msg = "<ConsultarInformacoesIntermediario>"
            . "<cnpj>$cnpjDeclarante</cnpj>"
            . "<GINN>$gIIN</GINN>"
            . "<TipoNI>$tpNI</TipoNI>"
            . "<NumeroIdentificacao>$numNI</NumeroIdentificacao>"
            . "</ConsultarInformacoesIntermediario>";
        return '';
    }
    
    /**
     * Consulta informações do Patrocinado
     *
     * @param string $cnpjDeclarante
     * @param string $cnpjPatrocinado
     * @param string $gIIN
     * @param array $aResp variável passada como referencia irá conter os retornos
     *                     em um array
     * @return string será retornado o xml de resposta do webservice
     */
    public function consultarInformacoesPatrocinado($cnpjDeclarante, $cnpjPatrocinado, $gIIN, &$aResp)
    {
        $msg = "<ConsultarInformacoesPatrocinado>"
            . "<cnpj>$cnpjDeclarante</cnpj>"
            . "<GINN>$gIIN</GINN>"
            . "<NumeroIdentificacao>$cnpjPatrocinado</NumeroIdentificacao>"
            . "</ConsultarInformacoesPatrocinado>";
        return '';
    }
   
    /**
     * Monta lote de eventos para envio
     * se os eventos não estiverem assinados assina antes de montar o lote
     *
     * @param array $aEv xml dos eventos a colocar no lote
     * @param array $aResp variável passada como referencia irá conter os retornos
     *                     em um array
     * @return string será retornado o xml de resposta do webservice
     * @throws InvalidArgumentException
     */
    
    public function enviaLote($aEv, &$aResp)
    {
        if (empty($aEv)) {
            return false;
        }
        $num = count($aEv);
        if ($num > 100) {
            throw new InvalidArgumentException("Somente podem ser mandados até 100 eventos por lote.");
        }
        $lote = "<eFinanceira "
                . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" "
                . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
                . "xmlns=\"http://www.eFinanceira.gov.br/schemas/envioLoteEventos/v1_0_1\">";
        $lote .= "<loteEventos>";
        $iCount = 0;
        foreach ($aEv as $evento) {
            $lote .= "<evento id=\"ID$iCount\">";
            $lote .= $evento;
            $lote .= "</evento";
        }
        $lote .= "</loteEventos>";
        $lote .= "</eFinanceira>";
        
        return '';
    }
}
