<?php

namespace Galilee\PPM\SDK\Chili\Entity;

/**
 * Class DocumentConstraint.
 */
class DocumentConstraint extends AbstractEntity
{
    const RESOURCE_NAME = 'DocumentConstraints';

    protected $availablePropertiesMap = [
        AbstractEntity::ID => '/item/@id',
    ];
}
