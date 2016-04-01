<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Exclusao
 *
 * @author administrador
 */
class Exclusao
{
   public function exclusao()
    {
        /**
         *
        <evtExclusao id="ID00000000001">
            <ideEvento>
                <tpAmb>1</tpAmb>
                <aplicEmi>2</aplicEmi>
                <verAplic>00000000000000000001</verAplic>
            </ideEvento>
            <ideDeclarante>
                <cnpjDeclarante>01234567891234</cnpjDeclarante>
            </ideDeclarante>
            <infoExclusao>
                <nrReciboEvento>{valor_nrRecibo_retorno}</nrReciboEvento>
            </infoExclusao>
        </evtExclusao>
         */
    }
    
    public function exclusaoFin()
    {
        /**
         *
        <evtExclusaoeFinanceira id="ID00000000001">
            <ideEvento>
                <tpAmb>1</tpAmb>
                <aplicEmi>2</aplicEmi>
                <verAplic>00000000000000000001</verAplic>
                </ideEvento>
            <ideDeclarante>
                <cnpjDeclarante>01234567891234</cnpjDeclarante>
            </ideDeclarante>
            <infoExclusaoeFinanceira>
                <nrReciboEvento>{valor_nrRecibo_retorno}</nrReciboEvento>
            </infoExclusaoeFinanceira>
        </evtExclusaoeFinanceira>
         */
    }
    
}
