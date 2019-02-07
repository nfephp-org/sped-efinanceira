<?php
namespace NFePHP\eFinanc\Exception;

/**
 * Class ProcessesException
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use NFePHP\eFinanc\Exception\ExceptionInterface;

class ProcessException extends \InvalidArgumentException implements ExceptionInterface
{
    public static $list = [
        2000 => "O numero máximo de eventos em um lote é 100, você está tentando "
            . "enviar {{msg}} eventos !",
        2001 => "Não temos um certificado disponível!",
        2002 => "Não foi passado um evento válido.",
        2003 => "O certificado do servidor não foi passado para a classe então "
            . "não é possivel a encriptação da mensagem.",
        2004 => "O certificado do servidor está vencido e deve ser substituido.",
        2005 => "O certificado do servidor fornecido não pertence ao commonName requerido. {{msg}}",
        2999 => ""
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
