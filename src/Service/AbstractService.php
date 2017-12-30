<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Api\Client;
use Galilee\PPM\SDK\Chili\Entity\AbstractResourceEntity;
use Galilee\PPM\SDK\Chili\Entity\SearchResult;
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
     * @todo
     *
     * @param $newName
     * @param $folderPath
     * @return bool
     */
    public function itemCopy($newName, $folderPath)
    {
        $params = array(
            'resourceName' => $this->getResourceName(),
            'itemID' => $this->getId(),
            'newName' => $newName,
            'folderPath' => $folderPath
        );
        $this->client->resourceItemCopy($params);
        return true;
    }


    /**
     * @param $name
     * @return SearchResult|null
     */
    public function search($name)
    {
        $result = null;
        if ($this->getResourceName()) {
            $params = array(
                'resourceName' => $this->getResourceName(),
                'name' => $name
            );
            $xmlString = $this->client->resourceSearch($params);
            $result = new SearchResult($this->client, $xmlString);
        }
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
        if ($this->getResourceName()) {
            $params = array(
                'resourceName' => $this->getResourceName(),
                'IDs' => $ids
            );
            $xmlString = $this->client->resourceSearchByIDs($params);
            $result = new SearchResult($this->client, $xmlString);
        }
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
