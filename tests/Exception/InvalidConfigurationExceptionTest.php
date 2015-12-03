<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException;

/**
 * Class InvalidConfigurationExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class InvalidConfigurationExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
     */
    public function testInvalidConfigurationExceptionShouldBeThrown()
    {
        throw new InvalidConfigurationException('Simple test message');
    }
}
