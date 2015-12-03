<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;

/**
 * Class ChiliSoapCallExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class ChiliSoapCallExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException
     */
    public function testChiliSoapCallExceptionShouldBeThrown()
    {
        throw new ChiliSoapCallException('Simple test message');
    }
}
