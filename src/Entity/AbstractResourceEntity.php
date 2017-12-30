<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\ChiliPublisher;

abstract class AbstractResourceEntity extends AbstractEntity
{

    abstract protected function getResourceName();

    public function getId()
    {
        return $this->get('id');
    }

    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @param string $type
     * @param int $pageNum
     * @return UrlInfo
     */
    public function itemGetURL($type, $pageNum = 1)
    {
        $params = array(
            'resourceName' => $this->getResourceName(),
            'itemID' => $this->getId(),
            'type' => $type,
            'pageNum' => $pageNum
        );

        $xmlString = $this->client->resourceItemGetURL($params);
        return new UrlInfo($this->client, $xmlString);
    }

    /**
     * @param int $pageNum
     * @return string
     */
    public function getThumbnailPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_THUMBNAIL, $pageNum);
        return $urlInfo->getUrl();
    }

    public function getMediumPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_MEDIUM, $pageNum);
        return $urlInfo->getUrl();
    }

    public function getFullPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_FULL, $pageNum);
        return $urlInfo->getUrl();
    }

    public function getSwfPreview($pageNum = 1)
    {
        $urlInfo = $this->itemGetURL(ChiliPublisher::PREVIEW_TYPE_SWF, $pageNum);
        return $urlInfo->getUrl();
    }
}