<?php

namespace Galilee\PPM\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Config\Config;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class SoapCall - used to perform chili Webservice invocations.
 */
class SoapCall implements InterfaceSoapCall
{
    /**  @var \SoapClient */
    protected $soapClient = null;

    /**@var Config $config */
    protected $config = null;

    protected $apiKey = null;

    /**
     * @param Config $config
     * @param string $apiKey
     */
    public function __construct(Config $config, $apiKey = null)
    {
        $this->config = $config;
        $this->apiKey = $apiKey;

        // init soap client for chili webservice calls
        $this->soapClient = new \SoapClient($this->config->getWsdlUrl(), ['cache_wsdl' => WSDL_CACHE_DISK]);
    }

    /**
     * Get the chili apiKey, parameter needed for the webservice calls.
     *
     * @return string
     */
    public function getApiKey()
    {
        if ($this->apiKey === null) {
            try {
                $soapXmlResponse = $this->soapClient->GenerateApiKey([
                    'environmentNameOrURL' => $this->config->getEnvironment(),
                    'userName' => $this->config->getLogin(),
                    'password' => $this->config->getPassword(),
                ]);

                $queryResult = Parser::get($soapXmlResponse->GenerateApiKeyResult, '/apiKey[1]/@key');
                $this->apiKey = $queryResult->item(0)->nodeValue;
            } catch (\Exception $e) {
                throw new ChiliSoapCallException($e->getMessage(), $e->getCode());
            }
        }

        return $this->apiKey;
    }

    /**
     * Perform a Chili webservice call.
     *
     * @param string $method
     * @param array  $params
     *
     * @return string $soapXmlResponse|null
     *
     * @throws ChiliSoapCallException
     */
    public function __call($method, $params = array())
    {
        if (!isset($params['apiKey'])) {
            $apiKey = $this->getApiKey();

            $soapParams = array('apiKey' => $apiKey);
            if (count($params) > 0) {
                $soapParams = $soapParams + $params[0];
            }

            $params = $soapParams;
        }
        // call chili API
        try {
            $soapXmlResponse = $this->soapClient->{$method}($params);

            return $soapXmlResponse->{$method.'Result'};
        } catch (\Exception $e) {
            throw new ChiliSoapCallException('Error on webservice call "'.$method.'" with message: '.$e->getMessage(), $e->getCode());
        }
    }
}
