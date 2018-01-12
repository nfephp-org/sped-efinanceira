<?php

namespace NFePHP\eFinanc\Common;

/**
 * Class FactoryId build ID event reference
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use DateTime;

class FactoryId
{
    /**
     * Build Id for EFDReinf event
     * @param int $tpInsc
     * @param string $nrInsc
     * @param DateTime $date
     * @param int $sequential
     * @return string
     */
    public static function build(
        $tpInsc,
        $nrInsc,
        DateTime $date,
        $sequential = 1
    ) {
        if (empty($sequential)) {
            $sequential = 1;
        }
        return "ID"
            . $tpInsc
            . str_pad($nrInsc, 14, '0', STR_PAD_RIGHT)
            . $date->format('YmdHis')
            . str_pad($sequential, 5, '0', STR_PAD_LEFT);
    }
}
