<?php

namespace Galilee\PPM\SDK\Chili\Config;

/**
 * Interface InterfaceConfig.
 */
interface InterfaceConfig
{
    /**
     * @return mixed
     */
    public function getLogin();

    /**
     * @return mixed
     */
    public function getPassword();

    /**
     * @return mixed
     */
    public function getWsdlUrl();

    /**
     * @return mixed
     */
    public function getEnvironment();

    /**
     * @return mixed
     */
    public function getPrivateUrl();

    /**
     * @return mixed
     */
    public function getPublicUrl();
}
