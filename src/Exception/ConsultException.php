<?php

namespace NFePHP\eFinanc\Exception;

/**
 * Class ConsultException
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use NFePHP\eFinanc\Exception\ExceptionInterface;

class ConsultException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function wrongArgument($msg = '')
    {
        return new static($msg);
    }
}
