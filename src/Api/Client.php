<?php
/**
 * @copyright © 2017 Galilée (www.galilee.fr)
 */

namespace Galilee\PPM\SDK\Chili\Api;

use Galilee\PPM\SDK\Chili\Config\Config;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Helper\XmlUtils;

/**
 * Class SoapCall
 * @package Galilee\PPM\SDK\Chili\Client
 *
 * Resource methods :
 *
 * @method string resourceSearchByIDs(array $params)
 * @method string resourceGetTree(array $params)
 *
 *
 * Resource Item Methods :
 *
 * @method string resourceItemGetXML(array $params)
 * @method string resourceItemSave(array $params)
 * @method string resourceItemCopy(array $params)
 * @method string resourceItemGetURL(array $params)
 * @method string ResourceItemDelete(array $params)
 *
 *
 * Documents Methods :
 *
 * @method string documentGetEditorURL(array $params)
 * @method string documentGetInfo(array $params)
 * @method string documentGetVariableValues(array $params)
 * @method string documentSetVariableValues(array $params)
 * @method string documentCreatePDF(array $params)
 *
 *
 * Others methods :
 *
 * @method string taskGetStatus(array $params)
 * @method string setWorkspaceAdministration(array $params)
 *
 */
class Client
{

    const CHILI_SESSION = '__galilee_chili_publisher__';

    /** @var  \SoapClient */
    protected $soapClient;

    /** @var Config */
    protected $config;

    /** @var string */
    protected $apiKey;

    private static $_instance = null;


    public static function getInstance(Config $config)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Client($config);
        }
        return self::$_instance;
    }

    private function __construct(Config $config)
    {
        $this->connect($config);
    }

    public function getConfig()
    {
        return $this->config;
    }
    /**
     * @param Config $config
     */
    private function connect(Config $config)
    {
        $this->config = $config;
        $this->soapClient = $this->getSoapClient($config->getWsdlUrl());
        $this->setApiKey();
    }

    private function getSoapClient($url)
    {
        return new \SoapClient($url);
    }

    /**
     * Set api key.
     * One api key per end user session.
     *
     * @return $this
     */
    private function setApiKey()
    {
        if (isset($_SESSION)) {
            if (!isset($_SESSION[self::CHILI_SESSION])) {
                $_SESSION[self::CHILI_SESSION] = $this->apiKey = $this->generateApiKey();
            } else {
                $this->apiKey = $_SESSION[self::CHILI_SESSION];
            }
        } else {
            $this->apiKey = $this->generateApiKey();
        }
        return $this;
    }


    /**
     * Get the chili apiKey, parameter needed for the webservice calls.
     *
     * @link http://docs.chili-publish.com/display/CPD4/Logging+in+to+the+WebServices
     * @return string
     * @throws ChiliSoapCallException
     */
    private function generateApiKey()
    {
        try {
            $rawXMLResponse = $this->soapClient->GenerateApiKey(
                array(
                    'environmentNameOrURL' => $this->config->getEnvironment(),
                    'userName'             => $this->config->getUsername(),
                    'password'             => $this->config->getPassword()
                ),
                array(
                    'wsdl_cache' => 1
                )
            );
            $xmlString = $this->getResponse($rawXMLResponse, 'GenerateApiKey');
            $domXml = XmlUtils::stringToDomDocument($xmlString);
            return $domXml->getElementsByTagName('apiKey')->item(0)->getAttribute('key');

        } catch (\Exception $e) {
            throw new ChiliSoapCallException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Perform a Chili webservice call.
     *
     * @param $methodName
     * @param array $params
     * @return string
     * @throws ChiliSoapCallException
     */
    public function __call($methodName, $params = [])
    {
        $params = array_merge(['apiKey' => $this->apiKey], $params[0]);
        $methodName = ucfirst(($methodName));
        try {
            /** @var \stdClass $rawXMLResponse */
            $rawXMLResponse = $this->soapClient->{$methodName}($params);
            return $this->getResponse($rawXMLResponse, $methodName);
        } catch (\Exception $e) {
            throw new ChiliSoapCallException('Error on webservice call "' . $methodName . '" with message: ' . $e->getMessage(), $e->getCode());
        }
    }

    private function getResponse(\stdClass $rawXMLResponse, $methodName)
    {
        return $rawXMLResponse->{$methodName . 'Result'};
    }
}
