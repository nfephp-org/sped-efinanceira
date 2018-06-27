<?php

namespace NFePHP\eFinanc\Common;

/**
 * Locate and identify last version folder of schemes XSD
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
class Schemes
{
    /**
     * @var string
     */
    private $lastschemes;
    
    /**
     * Constructor
     * Find last version scheme folder
     */
    public function __construct()
    {
        $scans = scandir('../schemes');
        $lastnumname = 0;
        $this->lastschemes = '';
        foreach ($scans as $folder) {
            if ($folder == '.' || $folder == '..') {
                continue;
            }
            $numname = str_replace(['v', '_'], '', $folder);
            if ($numname > $lastnumname) {
                $lastnumname = $numname;
                $this->lastschemes = str_replace('v', '', $folder);
            }
        }
    }
    
    /**
     * Return last scheme folder
     * @return string
     */
    public function last()
    {
        return $this->lastschemes;
    }
    
    /**
     * Return last scheme folder
     * @return string
     */
    public static function getLast()
    {
        $sch = new static();
        return $sch->last();
    }
}
