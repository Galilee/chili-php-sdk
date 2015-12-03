<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\InvalidXmlException;

/**
 * Class InvalidXmlExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class InvalidXmlExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidXmlException
     */
    public function testInvalidXmlExceptionShouldBeThrown()
    {
        throw new InvalidXmlException('Simple test message');
    }
}
