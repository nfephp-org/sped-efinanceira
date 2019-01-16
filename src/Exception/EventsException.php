<?php

namespace NFePHP\eFinanc\Exception;

/**
 * Class EventsException
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use NFePHP\eFinanc\Exception\ExceptionInterface;

class EventsException extends \InvalidArgumentException implements ExceptionInterface
{
    public static $list = [
        1000 => "Este evento [{{msg}}] não foi encontrado.",
        1001 => "Não foi passado o config.",
        1002 => "Não foram passados os dados do evento.",
        1003 => "Você deve passar os parâmetros de configuração num stdClass.",
        1004 => "JSON does not validate. Violations:\n{{msg}}",
        1005 => ""
    ];
    
    public static function wrongArgument($code, $msg = '')
    {
        $msg = self::replaceMsg(self::$list[$code], $msg);
        return new static($msg);
    }
    
    private static function replaceMsg($input, $msg)
    {
        return str_replace('{{msg}}', $msg, $input);
    }
}
