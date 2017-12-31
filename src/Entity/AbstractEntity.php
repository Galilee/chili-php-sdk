<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Api\Client;
use Galilee\PPM\SDK\Chili\Helper\XmlUtils;

abstract class AbstractEntity
{
    /** @var Client */
    protected $client;

    /** @var \DOMDocument */
    protected $dom;

    /**
     * AbstractEntity constructor.
     * @param Client $client
     * @param $xmlString
     */
    public function __construct(Client $client, $xmlString)
    {
        $this->client = $client;
        $this->setDomFromXmlString($xmlString);
    }

    public function setDomFromXmlString($xmlString)
    {
        $this->dom = XmlUtils::stringToDomDocument($xmlString);
        return $this;
    }

    protected function get($attributeName, $parentDomElement = null)
    {
        if (is_null($parentDomElement)) {
            $parentDomElement = $this->dom->documentElement;
        }

        return $parentDomElement->hasAttribute($attributeName)
            ? $parentDomElement->getAttribute($attributeName)
            : '';
    }

    protected function getBoolean($attributeName, $parentDomElement = null)
    {
        if (is_null($parentDomElement)) {
            $parentDomElement = $this->dom->documentElement;
        }

        return $parentDomElement->hasAttribute($attributeName)
            ? strtolower($parentDomElement->getAttribute($attributeName)) == 'true'
            : null;
    }

    public function getXmlString()
    {
        return $this->dom->saveXML();
    }

    public function __toString()
    {
        return htmlspecialchars($this->dom->saveXML());
    }
}