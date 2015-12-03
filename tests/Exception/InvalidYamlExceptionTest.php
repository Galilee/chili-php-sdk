<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\InvalidYamlException;

/**
 * Class InvalidYamlExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class InvalidYamlExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidYamlException
     */
    public function testInvalidYamlExceptionShouldBeThrown()
    {
        throw new InvalidYamlException('Simple test message');
    }
}
