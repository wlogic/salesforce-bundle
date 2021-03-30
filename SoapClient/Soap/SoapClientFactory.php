<?php
namespace Phpforce\SalesforceBundle\Soap;

use Phpforce\SalesforceBundle\Soap\TypeConverter;

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
        'ChildRelationship'     => 'Phpforce\SalesforceBundle\Result\ChildRelationship',
        'DeleteResult'          => 'Phpforce\SalesforceBundle\Result\DeleteResult',
        'DeletedRecord'         => 'Phpforce\SalesforceBundle\Result\DeletedRecord',
        'DescribeGlobalResult'  => 'Phpforce\SalesforceBundle\Result\DescribeGlobalResult',
        'DescribeGlobalSObjectResult' => 'Phpforce\SalesforceBundle\Result\DescribeGlobalSObjectResult',
        'DescribeSObjectResult' => 'Phpforce\SalesforceBundle\Result\DescribeSObjectResult',
        'DescribeTab'           => 'Phpforce\SalesforceBundle\Result\DescribeTab',
        'EmptyRecycleBinResult' => 'Phpforce\SalesforceBundle\Result\EmptyRecycleBinResult',
        'Error'                 => 'Phpforce\SalesforceBundle\Result\Error',
        'Field'                 => 'Phpforce\SalesforceBundle\Result\DescribeSObjectResult\Field',
        'GetDeletedResult'      => 'Phpforce\SalesforceBundle\Result\GetDeletedResult',
        'GetServerTimestampResult' => 'Phpforce\SalesforceBundle\Result\GetServerTimestampResult',
        'GetUpdatedResult'      => 'Phpforce\SalesforceBundle\Result\GetUpdatedResult',
        'GetUserInfoResult'     => 'Phpforce\SalesforceBundle\Result\GetUserInfoResult',
        'LeadConvert'           => 'Phpforce\SalesforceBundle\Request\LeadConvert',
        'LeadConvertResult'     => 'Phpforce\SalesforceBundle\Result\LeadConvertResult',
        'LoginResult'           => 'Phpforce\SalesforceBundle\Result\LoginResult',
        'MergeResult'           => 'Phpforce\SalesforceBundle\Result\MergeResult',
        'QueryResult'           => 'Phpforce\SalesforceBundle\Result\QueryResult',
        'SaveResult'            => 'Phpforce\SalesforceBundle\Result\SaveResult',
        'SearchResult'          => 'Phpforce\SalesforceBundle\Result\SearchResult',
        'SendEmailError'        => 'Phpforce\SalesforceBundle\Result\SendEmailError',
        'SendEmailResult'       => 'Phpforce\SalesforceBundle\Result\SendEmailResult',
        'SingleEmailMessage'    => 'Phpforce\SalesforceBundle\Request\SingleEmailMessage',
        'sObject'               => 'Phpforce\SalesforceBundle\Result\SObject',
        'UndeleteResult'        => 'Phpforce\SalesforceBundle\Result\UndeleteResult',
        'UpsertResult'          => 'Phpforce\SalesforceBundle\Result\UpsertResult',
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
