<?php

namespace DansMaCulotte\MondialRelay;

use Exception;
use Zend\Soap\Client as SoapClient;

class Client
{

    private $_soapClient;
    private $_soapOptions;
    private $_credentials;


    /**
     * Construct method to build soap client and options
     *
     * @param array  $credentials Contains site_id and and site_key
     * @param string $url         Url to use for SOAP client
     * @param array  $options     Options to use for SOAP client
     *
     * @throws \Exception
     */
    public function __construct($credentials, $url, $options)
    {
        if (isset($credentials['site_id']) == false) {
            throw new \Exception(
                'You must provide a site id to authenticate with MondialRelay Web Services'
            );
        }

        if (isset($credentials['site_key']) == false) {
            throw new \Exception(
                'You must provide a site key to authenticate with MondialRelay Web Services'
            );
        }

        $this->_soapOptions = array(
            'soap_version' => SOAP_1_1,
        );

        $this->_credentials = array(
            'Enseigne' =>  $credentials['site_id'],
            'site_key' =>  $credentials['site_key'],
        );

        $this->_soapClient = new SoapClient(
            $url,
            array_merge($this->_soapOptions, $options)
        );
    }


    /**
     * Proxy method to automaticaly inject credentials and options
     *
     * @param string $method Method to with SOAP web services
     * @param array $params Parameters to send with method
     *
     * @return Object
     * @throws Exception
     */
    public function soapExec(string $method, array $params)
    {
        $result = $this->_soapClient->$method(
            array_merge($this->_credentials, $params)
        );

        if ($result->WSI4_PointRelais_RechercheResult->STAT != 0)
        {
            throw new Exception(
                'Invalid'
            );
        }

        return $result;
    }
}