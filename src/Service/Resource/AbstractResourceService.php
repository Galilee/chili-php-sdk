<?php

namespace Galilee\PPM\SDK\Chili\Service\Resource;

use Galilee\PPM\SDK\Chili\Entity\ItemFileInfo;
use Galilee\PPM\SDK\Chili\Entity\Resource\AbstractResourceEntity;
use Galilee\PPM\SDK\Chili\Entity\SearchResult;
use Galilee\PPM\SDK\Chili\Entity\UrlInfo;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException;
use Galilee\PPM\SDK\Chili\Exception\PathTooLongException;
use Galilee\PPM\SDK\Chili\Exception\ResourceNotFoundException;
use Galilee\PPM\SDK\Chili\Service\AbstractService;

/**
 * Class AbstractResourceService
 * @package Galilee\PPM\SDK\Chili\Service\Resource
 */
abstract class AbstractResourceService extends AbstractService
{

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
     * @return AbstractResourceEntity
     * @throws ResourceNotFoundException
     * @throws ChiliSoapCallException
     */
    public function load($itemId)
    {
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
     * @throws EntityNotFoundException
     */
    public function itemCopy($itemId, $newName, $folderPath)
    {
        if (strlen($newName . $folderPath) >= 260 || strlen($folderPath) >= 248) {
            throw new PathTooLongException(
                'The fully qualified file name must be less than 260 characters, 
                and the directory name must be less than 248 characters.'
            );
        }
        if (!$this->isExists($itemId)) {
            throw new EntityNotFoundException($itemId . ' not found.');
        }

        $entity = null;
        $params = array(
            'resourceName' => $this->getResourceName(),
            'itemID' => $itemId,
            'newName' => $newName,
            'folderPath' => $folderPath
        );
        $xmlString = $this->client->resourceItemCopy($params);
        return new ItemFileInfo($this, $xmlString);
    }


    /**
     * Search resource.
     *
     * @param $name
     * @return SearchResult|null
     * @throws ChiliSoapCallException
     */
    public function search($name)
    {
        $result = null;
        $params = array(
            'resourceName' => $this->getResourceName(),
            'name' => $name
        );
        $xmlString = $this->client->resourceSearch($params);
        $result = new SearchResult($this, $xmlString);
        return $result;
    }

    /**
     * Search resource by Ids.
     *
     * @param array $ids
     * @return SearchResult|null
     * @throws ChiliSoapCallException
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
        $result = new SearchResult($this, $xmlString);
        return $result;
    }

    /**
     * Save Resource.
     *
     * @param $resourceName
     * @param $itemID
     * @param $xml
     * @return bool
     * @throws ChiliSoapCallException
     */
    public function save($resourceName, $itemID, $xml)
    {
        $params = array(
            'resourceName' => $resourceName,
            'itemID' => $itemID,
            'xml' => $xml
        );
        $this->client->resourceItemSave($params);
        return true;
    }

    /**
     * Get Resource Item url info.
     *
     * @param $resourceName
     * @param $itemID
     * @param $type
     * @param int $pageNum
     * @return UrlInfo
     * @throws ChiliSoapCallException
     */
    public function itemGetURL($resourceName, $itemID, $type, $pageNum = 1)
    {
        $params = array(
            'resourceName' => $resourceName,
            'itemID' => $itemID,
            'type' => $type,
            'pageNum' => $pageNum
        );
        $xmlString = $this->client->resourceItemGetURL($params);
        return new UrlInfo($this, $xmlString);
    }


    // ----------------
    // Helper methods
    // ----------------

    /**
     * Check if resource id exists.
     *
     * @param $itemId
     * @return bool
     * @throws ChiliSoapCallException
     */
    public function isExists($itemId)
    {
        $searchResult = $this->searchByIDs(array($itemId));
        return $searchResult->getCount() > 0;
    }


}
