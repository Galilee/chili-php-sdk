<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class AbstractEntity.
 */
abstract class AbstractEntity implements InterfaceEntity
{
    const NAME = 'name';
    const ID = 'id';

    /** @var string $xmlDef */
    protected $xmlDef = null;
    /** @var string $id */
    protected $id = null;

    /** @var array $availablePropertiesMap */
    protected $availablePropertiesMap = [];

    /**
     * Set the entity xml definition
     *
     * @param string $xml
     * @param bool $lazy - set lazily the entity ID
     */
    public function __construct($xml, $lazy = true)
    {
        $this->xmlDef = $xml;
        if (!$lazy) {
            $this->id = $this->getId();
        }
    }

    /**
     * Get the entity ID
     *
     * @return string|null
     *
     * @throws InvalidXpathExpressionException
     */
    public function getId(){
        if (!$this->id) {
            $nodeList = $this->get(self::ID);
            if($nodeList->length == 1){
                $this->id = $nodeList->item(0)->nodeValue;
            }
        }

        return $this->id;
    }

    /**
     * Get the entity XML definition
     *
     * @return string
     */
    public function getXmlDef()
    {
        return $this->xmlDef;
    }

    /**
     * General method to fetch information from the xmlDef (xml definition of the entity).
     *
     * @param string $key - key defined in $availablePropertiesMap or a XPath expression
     *
     * @return \DOMNodeList|null
     *
     * @throws InvalidXpathExpressionException
     */
    public function get($key)
    {
        $nodeList = null;
        if (isset($this->availablePropertiesMap[$key])) {
            $expression = $this->availablePropertiesMap[$key];
        } else {
            $expression = $key;
        }

        return Parser::get($this->xmlDef, $expression);
    }
}
