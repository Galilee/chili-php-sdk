<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Api\Client;

/**
 * Class AbstractService
 * @package Galilee\PPM\SDK\Chili\Service
 */
abstract class AbstractService
{

    /** @var Client */
    protected $client;

    /** @var  \DOMDocument */
    protected $dom;

    /**
     * Initialize the service.
     *
     * AbstractService constructor.
     * @param Client $client
     * @internal param Config $config
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

}
