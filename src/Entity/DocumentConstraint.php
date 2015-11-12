<?php

namespace Galilee\PPM\SDK\Chili\Entity;

/**
 * Class DocumentConstraint
 *
 * @package Galilee\PPM\SDK\Chili\Entity
 */
class DocumentConstraint extends AbstractEntity
{
    const RESOURCE_NAME = 'DocumentConstraints';

    protected $availablePropertiesMap = array(
        AbstractEntity::ID => '/item/@id',
    );
}
