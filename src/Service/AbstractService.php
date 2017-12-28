<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException;
use Galilee\PPM\SDK\Chili\Exception\ResourceNotFoundException;
use Galilee\PPM\SDK\Chili\Helper\Parser;
use Galilee\PPM\SDK\Chili\Client\SoapCall;
use Galilee\PPM\SDK\Chili\Client\ResultXml;
use Galilee\PPM\SDK\Chili\Entity\AbstractEntity;

/**
 * Class AbstractManager.
 */
abstract class AbstractService
{

    /** @var SoapCall|null */
    protected $soapCall = null;

    /** @var  AbstractEntity */
    protected $entity;


    /**
     * Initialize the service.
     *
     * AbstractService constructor.
     * @param SoapCall $soapCall
     */
    public function __construct(SoapCall $soapCall)
    {
        $this->soapCall = $soapCall;
    }

    abstract protected function getResourceName();

    abstract protected function initEntity(ResultXml $result);

    /**
     * @return AbstractEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param AbstractEntity $entity
     * @return AbstractService
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getSoapCall()
    {
        return $this->soapCall;
    }


    /**
     * Search resource by id (and type).
     *
     * @param $id
     * @return string
     * @throws EntityNotFoundException
     */
    public function searchResourceById($id)
    {
        $xmlResponse = $this->soapCall->ResourceSearchByIDs(array(
            'resourceName' => $this->getResourceName(),
            'IDs' => $id
        ));
        $result = Parser::get($xmlResponse, '//searchresults/item');
        if ($result->length != 1) {
            throw new EntityNotFoundException(
                'Resource not found for resourceName='
                . $this->getResourceName()
                . ' and id=' . $id);
        }
        $item = $result->item(0);
        return $item->ownerDocument->saveXML($item);
    }

    /**
     * Delete a Chili Resource Item by id (and type).
     *
     * @param $id
     * @return bool
     */
    protected function deleteResourceById($id)
    {
        $xmlResponse = $this->soapCall->ResourceItemDelete(array(
            'resourceName' => $this->getResourceName(),
            'itemID' => $id
        ));
        $result = Parser::get($xmlResponse, '/ok');
        return ($result->length == 1);
    }


    //New

    /**
     * @param $itemId
     * @return $this
     * @throws \Exception
     */
    public function load($itemId)
    {
        if ($this->isExists($itemId)) {
            $params = array(
                "resourceName" => $this->getResourceName(),
                "itemID" => $itemId
            );
            $resultXml = $this->soapCall->resourceItemGetXML($params);
            $this->setEntity($this->initEntity($resultXml));
        } else {
            throw new ResourceNotFoundException($itemId);
        }
        return $this;
    }

    /**
     * @param ResultXml $resultXml
     * @return $this
     */
    public function loadXML(ResultXml $resultXml)
    {
        $this->setEntity($this->initEntity($resultXml));
        return $this;
    }

    /**
     * @param $itemId
     * @return bool
     */
    public function isExists($itemId)
    {
        $params = array(
            'resourceName' => $this->getResourceName(),
            'IDs' => $itemId
        );
        /** @var ResultXml $resultXml */
        $resultXml = $this->soapCall->resourceSearchByIDs($params);
        /** @var \DOMDocument $xmlDom */
        $xmlDom = $resultXml->asDomXml();
        return (bool)$xmlDom->getElementsByTagName('item')->length;
    }


    public function getUrl($type, $pageNum)
    {
        $params = array(
            "resourceName" => $this->getResourceName(),
            "itemID" => $this->getEntity()->getId(),
            "type" => $type,
            "pageNum" => $pageNum
        );
        $url = '';

        /** @var ResultXml $resultXml */
        $resultXml = $this->soapCall->resourceItemGetURL($params);
        $xmlDom = $resultXml->asDomXml();
        if ($xmlDom->firstChild->hasAttribute("url")) {
            $url = $xmlDom->firstChild->getAttribute("url");
        }
        return $url;
    }

    public function copy($newName, $folderPath)
    {
        $params = array(
            "resourceName" => $this->getResourceName(),
            "itemID" => $this->getEntity()->getId(),
            "newName" => $newName,
            "folderPath" => $folderPath
        );
        $this->soapCall->resourceItemCopy($params);
        return true;
    }


    public function __call($method, $arguments)
    {
        if (!$this->getEntity()) {
            return '';
        }
        if (!method_exists($this->getEntity(), $method)) {
            return '';
        }
        return $this->getEntity()->$method($arguments);
    }
}
