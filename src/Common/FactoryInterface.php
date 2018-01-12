<?php

namespace NFePHP\eFinanc\Common;

/**
 * Factory interface
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2018
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
use NFePHP\Common\Certificate;

interface FactoryInterface
{
    public function alias();
    
    public function toXML();
    
    public function toJson();
    
    public function toStd();
    
    public function toArray();
    
    public function getId();
    
    public function getCertificate();
    
    public function setCertificate(Certificate $certificate);
}
