<?php

namespace NFePHP\eFinanc;

/**
 * Class eFinanc Event constructor
*
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use NFePHP\eFinanc\Exception\EventsException;

class Event
{
    /**
     * Relationship between the name of the event and its respective class
     * @var array
     */
    private static $available = [
        'evtaberturaefinanceira' => Factories\EvtAberturaeFinanceira::class,
        'evtcaddeclarante' => Factories\EvtCadDeclarante::class,
        'evtcadintermediario' => Factories\EvtCadIntermediario::class,
        'evtcadpatrocinado' => Factories\EvtCadPatrocinado::class,
        'evtexclusao' => Factories\EvtExclusao::class,
        'evtexclusaoefinanceira' => Factories\EvtExclusaoeFinanceira::class,
        'evtfechamentoefinanceira' => Factories\EvtFechamentoeFinanceira::class,
        'evtmovopfin' => Factories\EvtMovOpFin::class,
        'evtmovpp' => Factories\EvtMovPP::class,
        'evtmovopfinanual' => Factories\EvtMovOpFinAnual::class,
        'evtrerct' => Factories\EvtRERCT::class
    ];
    
    /**
     * Relationship between the code of the event and its respective name
     * @var array
     */
    private static $aliases = [
        'f1000' => 'evtaberturaefinanceira',
        'f2000' => 'evtcaddeclarante',
        'f2010' => 'evtcadintermediario',
        'f2020' => 'evtcadpatrocinado',
        'f3000' => 'evtmovopfin',
        'f3010' => 'evtmovopfinanual',
        'f4000' => 'evtfechamentoefinanceira',
        'f5000' => 'evtexclusao',
        'f6000' => 'evtmovpp',
        'f8000' => 'evtrerct',
        'f9000' => 'evtexclusaoefinanceira'
    ];
    
    /**
     * Call classes to build XML EFDReinf Event
     * @param string $name
     * @param array $arguments [config, std, certificate, $date]
     * @return object
     * @throws NFePHP\eFinanc\Exception\EventsException
     */
    public static function __callStatic($name, $arguments)
    {
        $name = str_replace('-', '', strtolower($name));
        $realname = $name;
        if (substr($name, 0, 1) == 'f') {
            if (!array_key_exists($name, self::$aliases)) {
                //este evento não foi localizado
                throw EventsException::wrongArgument(1000, $name);
            }
            $realname = self::$aliases[$name];
        }
        if (!array_key_exists($realname, self::$available)) {
            //este evento não foi localizado
            throw EventsException::wrongArgument(1000, $name);
        }
        $className = self::$available[$realname];
        
        if (empty($arguments[0])) {
            throw EventsException::wrongArgument(1001);
        }
        if (empty($arguments[1])) {
            throw EventsException::wrongArgument(1002, $name);
        }
        if (count($arguments) > 2 && count($arguments) < 4) {
            return new $className($arguments[0], $arguments[1], $arguments[2]);
        }
        if (count($arguments) > 3 && count($arguments) < 5) {
            return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
        }
        if (count($arguments) > 4 && count($arguments) < 6) {
            return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
        }
        return new $className($arguments[0], $arguments[1]);
    }
}
