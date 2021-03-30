<?php

namespace Phpforce\SalesforceBundle\SoapClient\Request;

class MergeRequest
{
    public $masterRecord;
    public $recordToMergeIds = array();
}
