<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Api\Client;
use Galilee\PPM\SDK\Chili\Entity\ItemFileInfo;
use Galilee\PPM\SDK\Chili\Entity\Resource\AbstractResourceEntity;
use Galilee\PPM\SDK\Chili\Entity\SearchResult;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Exception\PathTooLongException;
use Galilee\PPM\SDK\Chili\Exception\ResourceNotFoundException;

/**
 * Class AbstractManager.
 */
abstract class AbstractService
{

    /** @var Client */
    protected $client;

    /** @var  \DOMDocument */
    protected $dom;

    /**
     * Initialize the service.
     *
     * AbstractService constructor.
     * @param Client $client
     * @internal param Config $config
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    abstract protected function getResourceName();

    /**
     * @param $xmlString
     * @return AbstractResourceEntity
     */
    abstract protected function getEntity($xmlString);

    /**
     * Load resource item by Id.
     *
     * @param $itemId
     * @return AbstractResourceEntity|null
     * @throws ResourceNotFoundException
     * @throws ChiliSoapCallException
     */
    public function load($itemId)
    {
        $entity = null;
        if ($this->getResourceName()) {
            if ($this->isExists($itemId)) {
                $params = array(
                    'resourceName' => $this->getResourceName(),
                    'itemID' => $itemId
                );
                $xmlString = $this->client->resourceItemGetXML($params);
                $entity = $this->getEntity($xmlString);
            } else {
                throw new ResourceNotFoundException($itemId);
            }
        }
        return $entity;
    }

    /**
     * Copy resource.
     *
     * @param $itemId
     * @param $newName
     * @param $folderPath
     * @return ItemFileInfo
     * @throws PathTooLongException
     * @throws ChiliSoapCallException
     */
    public function itemCopy($itemId, $newName, $folderPath)
    {
        if (strlen($newName . $folderPath) >= 260 || strlen($folderPath) >= 248) {
            throw new PathTooLongException(
                'The fully qualified file name must be less than 260 characters, 
                and the directory name must be less than 248 characters.'
            );
        }
        $entity = null;
        $params = array(
            'resourceName' => $this->getResourceName(),
            'itemID' => $itemId,
            'newName' => $newName,
            'folderPath' => $folderPath
        );
        $xmlString = $this->client->resourceItemCopy($params);
        return new ItemFileInfo($this->client, $xmlString);
    }


    /**
     * @param $name
     * @return SearchResult|null
     */
    public function search($name)
    {
        $result = null;
        $params = array(
            'resourceName' => $this->getResourceName(),
            'name' => $name
        );
        $xmlString = $this->client->resourceSearch($params);
        $result = new SearchResult($this->client, $xmlString);
        return $result;
    }

    /**
     * @param $ids
     * @return SearchResult|null
     */
    public function searchByIDs($ids)
    {
        $ids = implode(';', $ids);
        $result = null;
        $params = array(
            'resourceName' => $this->getResourceName(),
            'IDs' => $ids
        );
        $xmlString = $this->client->resourceSearchByIDs($params);
        $result = new SearchResult($this->client, $xmlString);
        return $result;
    }


    // ----------------
    // Helper methods
    // ----------------

    /**
     * @param $itemId
     * @return bool
     */
    public function isExists($itemId)
    {
        $searchResult = $this->searchByIDs(array($itemId));
        return $searchResult->getCount() > 0;
    }


}
