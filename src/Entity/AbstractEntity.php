<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Client\ResultXml;

/**
 * Class AbstractEntity.
 */
abstract class AbstractEntity
{

    /** @var ResultXml $xmlDef */
    protected $xmlDef = null;


    /**
     * Set the entity xml definition
     *
     * AbstractEntity constructor.
     * @param ResultXml $resultXml
     */
    public function __construct(ResultXml $resultXml)
    {
        $this->xmlDef = $resultXml;
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return null \ string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->getAttribute('path');
    }

    /**
     *
     * @param $attributeName
     * @return null|string
     */
    public function getAttribute($attributeName) {
        $result = null;
        if ($this->xmlDef) {
            $res = $this->xmlDef->asSimpleXml();
            $result = (string)$res[$attributeName];
        }
        return $result;
    }


    /**
     * Get the entity XML definition.
     *
     * @return ResultXml
     */
    public function getXmlDef()
    {
        return $this->xmlDef;
    }
}
