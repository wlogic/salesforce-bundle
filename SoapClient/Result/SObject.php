<?php

namespace Phpforce\SalesforceBundle\SoapClient\Result;

use AllowDynamicProperties;

/**
 * Standard object
 *
 */
#[AllowDynamicProperties]
class SObject
{
    /**
     * @var string
     */
    public $Id;
    
    public function getId()
    {
        return $this->Id;
    }
}
