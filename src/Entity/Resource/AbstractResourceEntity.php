<?php

namespace Galilee\PPM\SDK\Chili\Entity\Resource;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity\AbstractEntity;
use Galilee\PPM\SDK\Chili\Entity\UrlInfo;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Service\Resource\AbstractResourceService;


abstract class AbstractResourceEntity extends AbstractEntity
{
    /** @var  AbstractResourceService */
    protected $service;

    abstract protected function getResourceName();

    /**
     * Get id attribute.
     *
     * @return string
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * Get name attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }


    /**
     * Save Resource.
     *
     * @return $this
     * @throws ChiliSoapCallException
     */
    public function save()
    {
        $this->service->save($this->getResourceName(), $this->getId(), $this->getXmlString());
        return $this;
    }

    /**
     * Get Item url info.
     *
     * @param string $type
     * @param int $pageNum
     * @return UrlInfo
     * @throws ChiliSoapCallException
     */
    public function itemGetURL($type, $pageNum = 1)
    {
        return $this->service->itemGetURL($this->getResourceName(), $this->getId(), $type, $pageNum);
    }

    /**
     * Get Thumbnail preview url.
     *
     * @param int $pageNum
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getThumbnailPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_THUMBNAIL, $pageNum);
        return $urlInfo->getUrl();
    }

    /**
     * Get Medium preview url.
     *
     * @param int $pageNum
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getMediumPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_MEDIUM, $pageNum);
        return $urlInfo->getUrl();
    }

    /**
     * Get Full preview url.
     *
     * @param int $pageNum
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getFullPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_FULL, $pageNum);
        return $urlInfo->getUrl();
    }

    /**
     * Get Swf preview url.
     *
     * @param int $pageNum
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getSwfPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_SWF, $pageNum);
        return $urlInfo->getUrl();
    }
}