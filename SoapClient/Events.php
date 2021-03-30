<?php
namespace Phpforce\SalesforceBundle\SoapClient;

final class Events
{
    const REQUEST    = 'phpforce.soap_client.request';
    const RESPONSE   = 'phpforce.soap_client.response';
    const FAULT      = 'phpforce.soap_client.fault';
}

