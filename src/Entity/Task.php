<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Helper\XmlUtils;
use Galilee\PPM\SDK\Chili\Service\Tasks;
use PHPUnit\Util\Xml;

class Task extends AbstractEntity
{
    /** @var  Tasks */
    protected $service;
    
    /** @var  \DOMDocument */
    protected $resultDom;
    
    public function setDomFromXmlString($xmlString)
    {
        parent::setDomFromXmlString($xmlString);
        // Result attribute contain xml string, So we have to convert it into DomDocument.
        $this->resultDom = $this->parseResultXml();
    }

    public function getStatus()
    {
        $xmlString = $this->service->getStatus($this->getId());
        $this->setDomFromXmlString($xmlString);
        return $this;
    }

    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->getBoolean('finished');
    }

    /**
     * @return bool
     */
    public function isSucceeded()
    {
        return $this->getBoolean('succeeded');
    }

    public function getErrorMessage()
    {
        return $this->get('errorMessage');
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->get('path');
    }

    /**
     * @return null|string
     */
    public function getRelativeUrl()
    {
        return $this->get('relativeURL');
    }


    /**
     * @return null|string
     */
    public function getResultUrl()
    {
        return $this->getResultAttribute('url');
    }

    /**
     * @return null|string
     */
    public function getResultPath()
    {
        return $this->getResultAttribute('path');
    }

    /**
     * @return null|string
     */
    public function getResultRelativeUrl()
    {
        return $this->getResultAttribute('relativeURL');
    }


    /**
     * @return \DOMDocument
     */
    protected function parseResultXml()
    {
        $resultString = $this->get('result');
        return XmlUtils::stringToDomDocument($resultString);
    }

    protected function getResultAttribute($attributeName)
    {
        $result = '';
        if ($rootNode = $this->resultDom->documentElement) {
            $result = $rootNode->getAttribute($attributeName);
        }
        return $result;
    }
    
}