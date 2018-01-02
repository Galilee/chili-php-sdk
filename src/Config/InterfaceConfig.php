<?php

namespace Galilee\PPM\SDK\Chili\Config;

/**
 * Interface InterfaceConfig.
 */
interface InterfaceConfig
{
    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return string
     */
    public function getWsdlUrl();

    /**
     * @return string
     */
    public function getEnvironment();

    /**
     * @return string
     */
    public function getProxyUrl();

}
