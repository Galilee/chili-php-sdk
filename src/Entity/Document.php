<?php

namespace Galilee\PPM\SDK\Chili\Entity;

/**
 * Class Document.
 */
class Document extends AbstractEntity
{
    const RESOURCE_NAME = 'Documents';

    protected $name = null;

    protected $availablePropertiesMap = [
        AbstractEntity::NAME => '/documentInfo/@name',
        AbstractEntity::ID => '/documentInfo/@id',
    ];

    /**
     * Get Document name.
     *
     * @return string
     *
     * @throws \Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException
     */
    public function getName()
    {
        if (!$this->name) {
            $nodeList = $this->get(AbstractEntity::NAME);
            if ($nodeList->length == 1) {
                $this->name = $nodeList->item(0)->nodeValue;
            }
        }

        return $this->name;
    }
}
