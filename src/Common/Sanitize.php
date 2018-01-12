<?php

namespace NFePHP\eFinanc\Common;

/**
 * Class Sanitize to clean entities from string
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
class Sanitize
{
    public static function text($text)
    {
        return htmlentities($text, ENT_QUOTES, 'UTF-8', false);
    }
}
