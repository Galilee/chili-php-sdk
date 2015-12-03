<?php

namespace Galilee\PPM\Tests\SDK\Chili\Exception;

use Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException;

/**
 * Class EntityNotFoundExceptionTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Exception
 * @backupGlobals disabled
 */

class EntityNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException
     */
    public function testEntityNotFoundExceptionShouldBeThrown()
    {
        throw new EntityNotFoundException('Simple test message');
    }
}
