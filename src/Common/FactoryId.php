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
     * @param int $sequential
     * @return string
     */
    public static function build($sequential)
    {
        if (empty($sequential)) {
            $sequential = 1;
        }
        return "ID".str_pad($sequential, 18, '0', STR_PAD_LEFT);
    }
}
