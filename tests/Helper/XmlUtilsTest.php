<?php

namespace Galilee\PPM\Tests\SDK\Chili\Helper;

use PHPUnit\Framework\TestCase;
use Galilee\PPM\SDK\Chili\Helper\XmlUtils;

/**
 * Class XmlUtilsTest.
 */
class XmlUtilsTest extends TestCase
{

    public function testStringToDomDocumentShouldReturnDOMDocumentObject()
    {
        $xmlString = '<?xml version="1.0"?><node>test</node>';
        $result = XmlUtils::stringToDomDocument($xmlString);
        $this->assertInstanceOf('\\DOMDocument', $result);
    }
}
