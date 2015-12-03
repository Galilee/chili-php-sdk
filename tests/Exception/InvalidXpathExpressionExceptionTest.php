<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException;

/**
 * Class InvalidXpathExpressionExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class InvalidXpathExpressionExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException
     */
    public function testInvalidXpathExpressionExceptionShouldBeThrown()
    {
        throw new InvalidXpathExpressionException('Simple test message');
    }
}
