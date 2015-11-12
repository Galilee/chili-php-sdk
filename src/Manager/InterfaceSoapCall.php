<?php

namespace Galilee\PPM\SDK\Chili\Manager;

/**
 * Interface InterfaceSoapCall
 *
 * @package Galilee\PPM\SDK\Chili\Manager
 */
interface InterfaceSoapCall {

    /**
     * Get the chili apiKey, parameter needed for the webservice calls.
     *
     * @return string
     */
    public function getApiKey();
}