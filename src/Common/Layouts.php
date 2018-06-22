<?php

namespace NFePHP\eFinanc\Common;

/**
 * Locate and identify the version of each event
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
class Layouts
{
    /**
     * @var array
     */
    public $versions;
    
    
    /**
     * Constructor
     * @param string $config
     */
    public function __construct($config)
    {
        $std = json_decode($config);
        $version = $std->eventoVersion;
        $schemasFolder = realpath(
            __DIR__."/../../schemes/v$version"
        );
        $this->read($schemasFolder);
    }
    
    /**
     * Locate folder indicated in config and read each xsd
     * extract version and save in property
     *
     * @param string $schemasFolder
     */
    protected function read($schemasFolder)
    {
        $list = glob($schemasFolder.'/*.xsd');
        foreach ($list as $file) {
            $xsd = str_replace($schemasFolder.'/', '', $file);
            $name = explode('-', $xsd);
            $ver = str_replace(['.xsd', 'v'], '', $name[1]);
            $this->versions[$name[0]] = $ver;
        }
    }
}
