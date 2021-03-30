<?php
namespace Phpforce\SalesforceBundle\SoapClient\Request;

class SingleEmailMessage extends BaseEmail
{
    public $bccAddresses;
    public $ccAddresses;
    public $charset;
    public $fileAttachments;
    public $htmlBody;
    public $plainTextBody;
    public $targetObjectId;
    public $toAddresses;
    public $whatId;
    public $orgWideEmailAddressId;
}
