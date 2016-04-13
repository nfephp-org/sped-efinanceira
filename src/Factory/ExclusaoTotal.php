<?php
namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de exclusao total da eFinanceira
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc\Factory\ExclusaoTotal
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class ExclusaoTotal extends Factory
{
    
     /**
     * Objeto Dom::class Tag info
      *
     * @var Dom
     */
    protected $info;
    /**
     * estabelece qual a tag será assinada
     *
     * @var string
     */
    protected $signTag = 'evtExclusaoeFinanceira';
    
    /**
     * Faz a premontagem se necessário
     *
     * @return
     */
    protected function premonta()
    {
        return;
    }
    
    /**
     * Cria a tag info
     *
     * @param  string $nrReciboEventoAbertura
     * @return Dom
     */
    public function tagInfo($nrReciboEventoAbertura)
    {
        $identificador = 'tag infoExclusao ';
        $info = $this->dom->createElement("infoExclusaoeFinanceira");
        $this->dom->addChild(
            $info,
            "nrReciboEvento",
            $nrReciboEventoAbertura,
            true,
            $identificador . "Numero do recibo do evento de abertura da eFinanceira que se quer excluir"
        );
        $this->info = $info;
        return $info;
    }
}
