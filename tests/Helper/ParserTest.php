<?php

namespace Galilee\PPM\Tests\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class ParserTest.
 *
 * @backupGlobals disabled
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        parent::setUp();

        $this->xml = <<<XML
<?xml version="1.0" encoding="iso-8859-1"?>
<searchresults><item name="Constraint 01" id="1e778fb7-ed93-4ca2-aadf-ce5339868fe5" relativePath="" hasPreviewErrors="false" preserveExistingDocConstraints="False" preserveExistingLayerConstraints="False" preserveExistingPageConstraints="False" iconURL="galilee/download.aspx?type=thumb&amp;resourceName=DocumentConstraints&amp;id=1e778fb7-ed93-4ca2-aadf-ce5339868fe5&amp;apiKey=Q0Jyyu+UBIXcY38Te0VMOY79qDH4V84oKkVWXQ1RADVDxXS0bFDId+cBVpEkBc2g_kA3RmczjTqZ4ySIJWqPEg==&amp;pageNum=1"><constraintItems></constraintItems></item></searchresults>
XML;
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->xml = null;
    }

    /**
     * Test 1 : Parser::get() returns \DOMNodeList.
     */
    public function testGetShouldReturnDOMNodeList()
    {
        $expression = '//searchresults/item';
        $result = Parser::get($this->xml, $expression);

        $this->assertTrue($result instanceof \DOMNodeList);
        $this->assertEquals($result->length, 1);
    }

    /**
     * Test 2 : Parser::get() throws exception.
     *
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException
     */
    public function testGetWithInvalidExpressionShouldThrowException()
    {
        $expression = 'item@name'; //invalid Xpath expression
        $result = Parser::get($this->xml, $expression);
    }

    /**
     * Test 3 : Parser::xmlToDomDoc() returns \DOMDocument.
     */
    public function testXmlToDomDocShouldReturnDOMDocumentObject()
    {
        $result = Parser::xmlToDomDoc($this->xml);
        $this->assertInstanceOf('\\DOMDocument', $result);
    }
}
