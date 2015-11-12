<?php

namespace Galilee\PPM\SDK\Chili\Entity;

/**
 * Class Workspace.
 */
class Workspace extends AbstractEntity
{
    const RESOURCE_NAME = 'WorkSpaces';

    protected $availablePropertiesMap = [
        AbstractEntity::NAME => '/item/@name',
        AbstractEntity::ID   => '/item/@id',
    ];
}
