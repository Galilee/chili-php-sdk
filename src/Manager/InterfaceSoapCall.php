<?php

namespace Galilee\PPM\SDK\Chili\Manager;

/**
 * Interface InterfaceSoapCall.
 */
interface InterfaceSoapCall
{
    /**
     * Get the chili apiKey, parameter needed for the webservice calls.
     *
     * @return string
     */
    public function getApiKey();
}
