<?php

namespace Galilee\PPM\SDK\Chili\Config;

class Config implements InterfaceConfig
{
    protected $login;
    protected $password;
    protected $wsdlUrl;
    protected $environment;
    protected $privateUrl;
    protected $publicUrl;

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getWsdlUrl()
    {
        return $this->wsdlUrl;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getPrivateUrl()
    {
        return $this->privateUrl;
    }

    /**
     * @return string
     */
    public function getPublicUrl()
    {
        return $this->publicUrl;
    }

    // Setters

    /**
     * @param string $login
     *
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $wsdlUrl
     *
     * @return $this
     */
    public function setWsdlUrl($wsdlUrl)
    {
        $this->wsdlUrl = $wsdlUrl;
        return $this;
    }

    /**
     * @param string $environment
     *
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @param string $privateUrl
     *
     * @return $this
     */
    public function setPrivateUrl($privateUrl)
    {
        $this->privateUrl = $privateUrl;
        return $this;
    }

    /**
     * @param string $publicUrl
     *
     * @return $this
     */
    public function setPublicUrl($publicUrl)
    {
        $this->publicUrl = $publicUrl;
        return $this;
    }
}
