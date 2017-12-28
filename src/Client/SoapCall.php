<?php
/**
 * @author Géraud ISSERTES <gissertes@galilee.fr>
 * @copyright © 2017 Galilée (www.galilee.fr)
 *
 */

namespace Galilee\PPM\SDK\Chili\Client;

use Galilee\PPM\SDK\Chili\Config\Config;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;

/**
 * Class SoapCall
 * @package Galilee\PPM\SDK\Chili\Client
 *
 * @method ResultXml resourceSearchByIDs(array $params)
 * @method ResultXml resourceItemGetXML(array $params)
 * @method ResultXml resourceItemSave(array $params)
 * @method ResultXml resourceGetTree(array $params)
 * @method ResultXml resourceItemGetURL(array $params)
 * @method ResultXml documentGetEditorURL(array $params)
 * @method ResultXml setWorkspaceAdministration(array $params)
 * @method ResultXml documentGetInfo(array $params)
 * @method ResultXml ResourceItemDelete(array $params)
 * @method ResultXml documentGetVariableValues(array $params)
 * @method ResultXml documentSetVariableValues(array $params)
 * @method ResultXml documentCreatePDF(array $params)
 * @method ResultXml taskGetStatus(array $params)
 *
 */
class SoapCall
{

    const CHILI_SESSION = '__gallee_chilipublisher__';

    protected $soapClient;

    /** @var Config null */
    protected $config = null;

    public $apiKey;

    private static $_instance = null;


    public static function getInstance(Config $config)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new SoapCall($config);
        }
        return self::$_instance;
    }

    private function __construct(Config $config)
    {
        $this->connect($config);
    }

    /**
     * @param Config $config
     */
    public function connect(Config $config)
    {
        $this->config = $config;
        $this->soapClient = new \SoapClient($config->getWsdlUrl());
        $this->setApiKey();
    }

    /**
     * Set api key.
     * One api key per end user session.
     *
     * @return $this
     */
    public function setApiKey()
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
    public function generateApiKey()
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
            $result = $this->setResult($rawXMLResponse, 'GenerateApiKey');
            $domXml = $result->asDomXml();
            return $domXml->getElementsByTagName("apiKey")->item(0)->getAttribute("key");

        } catch (\Exception $e) {
            throw new ChiliSoapCallException($e->getMessage(), $e->getCode());
        }

    }


    /**
     *
     * Perform a Chili webservice call.
     *
     * @param $methodName
     * @param array $params
     * @return ResultXml
     * @throws ChiliSoapCallException
     */
    public function __call($methodName, $params = [])
    {
        $params = array_merge(['apiKey' => $this->apiKey], $params[0]);
        $methodName = ucfirst(($methodName));
        try {
            $rawXMLResponse = $this->soapClient->{$methodName}($params);
            return $this->setResult($rawXMLResponse, $methodName);
        } catch (\Exception $e) {
            throw new ChiliSoapCallException('Error on webservice call "' . $methodName . '" with message: ' . $e->getMessage(), $e->getCode());
        }
    }

    protected function setResult($rawXMLResponse, $methodName)
    {
        return new ResultXml($rawXMLResponse->{$methodName . 'Result'});
    }
}