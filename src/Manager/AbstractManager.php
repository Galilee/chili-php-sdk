<?php

namespace Galilee\PPM\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Config\Config;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException;
use Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class AbstractManager.
 */
abstract class AbstractManager
{
    /** @var Config|null  */
    protected $config = null;
    /** @var SoapCall|null  */
    protected $soapCall = null;

    /**
     * Initialize the manager configuration and the soap client.
     *
     * @param Config $config - see ConfigService->getConfig() to create this parameter
     * @param string $apiKey
     * @param bool $initSoapCall
     *
     */
    public function __construct(Config $config, $apiKey = null, $initSoapCall = true)
    {
        // set the chili config
        $this->config = $config;

        //init soap client
        if ($initSoapCall === true) {
            $this->soapCall = new SoapCall($config, $apiKey);
        }
    }

    /**
     * Set the soap service manager
     *
     * @param InterfaceSoapCall $soapCall
     */
    public function setSoapCall(InterfaceSoapCall $soapCall)
    {
        $this->soapCall = $soapCall;

        return $this;
    }

    /**
     * Get soap call
     *
     * @return SoapCall|null
     *
     */
    public function getSoapCall()
    {
        return $this->soapCall;
    }

    /**
     * Search resource by id (and type)
     *
     * @param string $id
     * @param string $resourceName
     *
     * @return \DOMNodeList|\DOMNode|null
     *
     * @throws EntityNotFoundException
     */
    public function searchResourceById($id, $resourceName)
    {
        $xmlResponse = $this->soapCall->ResourceSearchByIDs(array(
            'resourceName' => $resourceName,
            'IDs'          => $id
        ));
        $result = Parser::get($xmlResponse, '//searchresults/item');
        if ($result->length == 1) {
            $item = $result->item(0);

            return $item->ownerDocument->saveXML($item);
        }

        throw new EntityNotFoundException('Resource not found for resourceName=' . $resourceName . ' and id=' . $id);
    }

    /**
     * Delete a Chili Resource Item by id (and type)
     *
     * @param $id
     * @param $resourceName
     *
     * @return bool
     *
     * @throws ChiliSoapCallException
     */
    protected function deleteResourceById($id, $resourceName)
    {
        $xmlResponse = $this->soapCall->ResourceItemDelete(array(
            'resourceName' => $resourceName,
            'itemID'       => $id
        ));

        $result = Parser::get($xmlResponse, '/ok');

        return ($result->length == 1);
    }
}
