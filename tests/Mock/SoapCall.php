<?php

namespace Galilee\PPM\Tests\SDK\Chili\Mock;

use Galilee\PPM\SDK\Chili\Config\Config;
use Galilee\PPM\SDK\Chili\Manager\InterfaceSoapCall;

/**
 * Class SoapCall
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Mock
 */
class SoapCall implements InterfaceSoapCall{

    protected $apiKey;

    protected $config;

    protected $scenarioName;

    private $mockDirectoryPath;

    /**
     *
     *
     * @param Config $config
     * @param string $apiKey
     */
    public function __construct(Config $config, $mockDirectoryPath, $apiKey = null)
    {
        $this->apiKey = $apiKey;
        $this->mockDirectoryPath = $mockDirectoryPath;

    }

    public function getApiKey()
    {
        return $this->apiKey;
    }


    public function setScenarioName($scenarioName)
    {
        $this->scenarioName = $scenarioName;

        return $this;
    }

    public function getScenarioName()
    {
        return $this->scenarioName;
    }

    /**
     * Get content from a Mock xml file
     *
     * @param string $method
     * @param array $params
     * @return string
     */
    public function __call($method, $params = array())
    {
        $mockFilename = $this->mockDirectoryPath . DIRECTORY_SEPARATOR . 'MOCK_' . $method . '_' . $this->scenarioName . '.xml';
        if (!file_exists($mockFilename)) {
            throw new \Exception('Unable to find mock file: ' . $mockFilename);
        } else {
            $soapXmlResponse = file_get_contents($mockFilename);
            $domXml = new \DOMDocument();
            $domXml->loadXML($soapXmlResponse);

            return $domXml->getElementsByTagName($method . 'Result')->item(0)->nodeValue;
        }
    }
}