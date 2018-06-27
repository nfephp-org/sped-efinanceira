<?php


namespace NFePHP\eFinanc\Common;


class Schemes
{
    private $lastfolder;
    
    public function __construct()
    {
        $scans = scandir('../schemes');
        $lastnumname = 0;
        $this->lastfolder = '';
        foreach($scans as $folder) {
            if ($folder == '.' || $folder == '..') {
                continue;
            }
            $numname = str_replace(['v', '_'], '', $folder);
            if ($numname > $lastnumname) {
                $lastnumname = $numname;
                $this->lastfolder = $folder;
            }
        }    
    }
    
    public function last()
    {
        return $this->lastfolder;
    }
    
    public static function getLast()
    {
        $sch = new static();
        return $sch->last();
    }
}
