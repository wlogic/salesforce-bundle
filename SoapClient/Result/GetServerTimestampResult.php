<?php

namespace Phpforce\SalesforceBundle\SoapClient\Result;

class GetServerTimestampResult
{
    protected $timestamp;

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
