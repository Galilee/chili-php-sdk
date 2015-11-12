<?php

namespace Galilee\PPM\SDK\Chili\Entity;

/**
 * Class ViewPreference.
 */
class ViewPreference extends AbstractEntity
{
    const RESOURCE_NAME = 'ViewPreferences';

    protected $availablePropertiesMap = [
        AbstractEntity::NAME => '/item/@name',
        AbstractEntity::ID   => '/item/@id',
    ];
}
