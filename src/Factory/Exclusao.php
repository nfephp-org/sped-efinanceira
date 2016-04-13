<?php
namespace NFePHP\eFinanc\Factory;

/**
 * Classe construtora do evento de exclusao
 *
 * @category  NFePHP
 * @package   NFePHP\eFinanc\Factory\Exclusao
 * @copyright Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */

use NFePHP\eFinanc\Factory\Factory;

class Exclusao extends Factory
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
    protected $signTag = 'evtExclusao';
    
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
     * @param  string $nrReciboEvento
     * @return Dom
     */
    public function tagInfo($nrReciboEvento)
    {
        $identificador = 'tag infoExclusao ';
        $info = $this->dom->createElement("infoExclusao");
        $this->dom->addChild(
            $info,
            "nrReciboEvento",
            $nrReciboEvento,
            true,
            $identificador . "Numero do recibo do evento que se quer excluir"
        );
        $this->info = $info;
        return $info;
    }
}
