<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException;

/**
 * Interface InterfaceEntity.
 */
interface InterfaceEntity
{
    /**
     * Get the entity ID
     *
     * @return string|null
     */
    public function getId();

    /**
     * Get the entity XML definition
     *
     * @return string|null
     */
    public function getXmlDef();

    /**
     * General method to fetch information from the xmlDef (xml definition of an entity).
     *
     * @param string $key - key defined in $availablePropertiesMap or a XPath expression
     *
     * @return \DOMNodeList|null
     *
     * @throws InvalidXpathExpressionException
     */
    public function get($key);
}
