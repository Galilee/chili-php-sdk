<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\InvalidJsonException;

/**
 * Class InvalidJsonExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class InvalidJsonExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidJsonException
     */
    public function testInvalidJsonExceptionShouldBeThrown()
    {
        throw new InvalidJsonException('Simple test message');
    }
}
