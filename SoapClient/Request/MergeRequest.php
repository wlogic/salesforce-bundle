<?php

namespace Phpforce\SalesforceBundle\Request;

class MergeRequest
{
    public $masterRecord;
    public $recordToMergeIds = array();
}
