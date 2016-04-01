<?php

namespace NFePHP\eFinanc;

class Tools
{
    public function __construct()
    {
        
    }
    
    public function consultarInformacoesCadastrais()
    {
        
    }
    
    public function consultarInformacoesIntermediario()
    {
        
    }
    
    public function consultarInformacoesPatrocinado()
    {
        
    }
    
    public function consultarListaEFinanceira()
    {
        
    }
    
    public function consultarInformacoesMovimento()
    {
        
    }
    
    public function assina($xml, $tag)
    {
        
    }
    
    /**
     * Monta lote de eventos para envio
     * se os eventos não estiverem assinados assina antes de montar o lote
     * @param array $aEv xml dos eventos a colocar no lote
     * @param array $aResp variável passada como referencia irá conter os retornos em um array
     * @return string
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
        $lote = "<eFinanceira xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.eFinanceira.gov.br/schemas/envioLoteEventos/v1_0_1\">";
        $lote .= "<loteEventos>";
        $iCount = 0;
        foreach ($aEv as $evento) {
            $lote .= "<evento id=\"ID$iCount\">";
            $lote .= $evento;
            $lote .= "</evento";
        }
        $lote .= "</loteEventos>";
        $lote .= "</eFinanceira>";
        return $response;
    }
    
    protected function checkSignature($evt)
    {
        
    }
}
