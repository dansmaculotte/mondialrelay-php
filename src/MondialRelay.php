<?php

namespace DansMaCulotte\MondialRelay;

use DansMaCulotte\MondialRelay\Exceptions\Exception;
use Zend\Soap\Client as SoapClient;

class MondialRelay
{
    /** @var */
    public $soapClient;

    /** @var array */
    public $soapOptions = [
        'soap_version' => SOAP_1_2,
    ];

    /** @var array */
    private $credentials = [];


    /**
     * Construct method to build soap client and options
     *
     * @param array $credentials Contains site_id and and site_key
     * @param string $url Url to use for SOAP client
     * @param array $options Options to use for SOAP client
     * @throws Exception
     */
    public function __construct(array $credentials, string $url = null, array $options = [])
    {
        if (isset($credentials['site_id']) == false) {
            throw Exception::invalidCredentials("site id");
        }

        if (isset($credentials['site_key']) == false) {
            throw Exception::invalidCredentials("site key");
        }

        $this->credentials = [
            'Enseigne' =>  $credentials['site_id'],
            'Key' =>  $credentials['site_key'],
        ];


        $this->soapClient = new SoapClient(
            $url,
            array_merge($this->soapOptions, $options)
        );
    }


    private function getSecurityKey(array $params)
    {
        $security = $this->credentials['Enseigne'];
        foreach ($params as $param) {
            $security.= $param;
        }
        $security.= $this->credentials['Key'];

        return strtoupper(md5($security));
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
        $params['Security'] = $this->getSecurityKey($params);
        $params['Enseigne'] = $this->credentials['Enseigne'];

        $result = $this->soapClient->$method($params);

        return $result;
    }
}
