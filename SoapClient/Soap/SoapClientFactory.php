<?php
namespace Phpforce\SalesforceBundle\SoapClient\Soap;

use Phpforce\SalesforceBundle\SoapClient\Soap\TypeConverter;

/**
 * Factory to create a \SoapClient properly configured for the Salesforce SOAP
 * client
 */
class SoapClientFactory
{
    /**
     * Default classmap
     *
     * @var array
     */
    protected $classmap = array(
        'ChildRelationship'     => 'Phpforce\SalesforceBundle\SoapClient\Result\ChildRelationship',
        'DeleteResult'          => 'Phpforce\SalesforceBundle\SoapClient\Result\DeleteResult',
        'DeletedRecord'         => 'Phpforce\SalesforceBundle\SoapClient\Result\DeletedRecord',
        'DescribeGlobalResult'  => 'Phpforce\SalesforceBundle\SoapClient\Result\DescribeGlobalResult',
        'DescribeGlobalSObjectResult' => 'Phpforce\SalesforceBundle\SoapClient\Result\DescribeGlobalSObjectResult',
        'DescribeSObjectResult' => 'Phpforce\SalesforceBundle\SoapClient\Result\DescribeSObjectResult',
        'DescribeTab'           => 'Phpforce\SalesforceBundle\SoapClient\Result\DescribeTab',
        'EmptyRecycleBinResult' => 'Phpforce\SalesforceBundle\SoapClient\Result\EmptyRecycleBinResult',
        'Error'                 => 'Phpforce\SalesforceBundle\SoapClient\Result\Error',
        'Field'                 => 'Phpforce\SalesforceBundle\SoapClient\Result\DescribeSObjectResult\Field',
        'GetDeletedResult'      => 'Phpforce\SalesforceBundle\SoapClient\Result\GetDeletedResult',
        'GetServerTimestampResult' => 'Phpforce\SalesforceBundle\SoapClient\Result\GetServerTimestampResult',
        'GetUpdatedResult'      => 'Phpforce\SalesforceBundle\SoapClient\Result\GetUpdatedResult',
        'GetUserInfoResult'     => 'Phpforce\SalesforceBundle\SoapClient\Result\GetUserInfoResult',
        'LeadConvert'           => 'Phpforce\SalesforceBundle\SoapClient\Request\LeadConvert',
        'LeadConvertResult'     => 'Phpforce\SalesforceBundle\SoapClient\Result\LeadConvertResult',
        'LoginResult'           => 'Phpforce\SalesforceBundle\SoapClient\Result\LoginResult',
        'MergeResult'           => 'Phpforce\SalesforceBundle\SoapClient\Result\MergeResult',
        'QueryResult'           => 'Phpforce\SalesforceBundle\SoapClient\Result\QueryResult',
        'SaveResult'            => 'Phpforce\SalesforceBundle\SoapClient\Result\SaveResult',
        'SearchResult'          => 'Phpforce\SalesforceBundle\SoapClient\Result\SearchResult',
        'SendEmailError'        => 'Phpforce\SalesforceBundle\SoapClient\Result\SendEmailError',
        'SendEmailResult'       => 'Phpforce\SalesforceBundle\SoapClient\Result\SendEmailResult',
        'SingleEmailMessage'    => 'Phpforce\SalesforceBundle\SoapClient\Request\SingleEmailMessage',
        'sObject'               => 'Phpforce\SalesforceBundle\SoapClient\Result\SObject',
        'UndeleteResult'        => 'Phpforce\SalesforceBundle\SoapClient\Result\UndeleteResult',
        'UpsertResult'          => 'Phpforce\SalesforceBundle\SoapClient\Result\UpsertResult',
    );

    /**
     * Type converters collection
     *
     * @var TypeConverter\TypeConverterCollection
     */
    protected $typeConverters;

    /**
     * @param string $wsdl Path to WSDL file
     * @param array $soapOptions
     * @return SoapClient
     */
    public function factory($wsdl, array $soapOptions = array())
    {
        $defaults = array(
            'trace'      => 1,
            'features'   => \SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap'   => $this->classmap,
            'typemap'    => $this->getTypeConverters()->getTypemap(),
            'cache_wsdl' => \WSDL_CACHE_MEMORY
        );

        $options = array_merge($defaults, $soapOptions);

        return new SoapClient($wsdl, $options);
    }

    /**
     * test
     *
     * @param string $soap SOAP class
     * @param string $php  PHP class
     */
    public function setClassmapping($soap, $php)
    {
        $this->classmap[$soap] = $php;
    }

    /**
     * Get type converter collection that will be used for the \SoapClient
     *
     * @return TypeConverter\TypeConverterCollection
     */
    public function getTypeConverters()
    {
        if (null === $this->typeConverters) {
            $this->typeConverters = new TypeConverter\TypeConverterCollection(
                array(
                    new TypeConverter\DateTimeTypeConverter(),
                    new TypeConverter\DateTypeConverter()
                )
            );
        }

        return $this->typeConverters;
    }

    /**
     * Set type converter collection
     *
     * @param TypeConverter\TypeConverterCollection $typeConverters Type converter collection
     *
     * @return SoapClientFactory
     */
    public function setTypeConverters(TypeConverter\TypeConverterCollection $typeConverters)
    {
        $this->typeConverters = $typeConverters;

        return $this;
    }
}
