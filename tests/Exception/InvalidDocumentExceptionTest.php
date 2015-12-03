<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\InvalidDocumentException;

/**
 * Class InvalidDocumentExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class InvalidDocumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidDocumentException
     */
    public function testInvalidDocumentExceptionShouldBeThrown()
    {
        throw new InvalidDocumentException('Simple test message');
    }
}
