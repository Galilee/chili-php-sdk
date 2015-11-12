<?php

namespace Galilee\PPM\Tests\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Entity\Document;

/**
 * Class DocumentTest.
 *
 * @backupGlobals disabled
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        parent::setUp();

        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'document.xml'));
        $docInfo = $xml->getElementsByTagName('DocumentGetInfoResult')->item(0);

        $this->xml = $docInfo->textContent;
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->xml = null;
    }

    /**
     * Test 1 : DocumentEntity->getName() return document name.
     */
    public function testGetNameShouldReturnStringDocumentName()
    {
        $document = new Document($this->xml);
        $name = $document->getName();
        $this->assertEquals($name, 'snippetHotel');
    }

    /**
     * Test 2 : DocumentEntity->getId() returns document id.
     */
    public function testGetIdShouldReturnStringDocumentId()
    {
        $document = new Document($this->xml);
        $id = $document->getId();
        $this->assertEquals($id, '3728e7ef-adcb-44a3-83f7-d2949edd9cbe');
    }

    /**
     * Test 3 : DocumentEntity->get() returns custom attribute value.
     */
    public function testGeneralGetShouldReturnCustomAttribute()
    {
        $document = new Document($this->xml);
        $nodeList = $document->get('/documentInfo/@numPages');
        $numPages = $nodeList->item(0)->nodeValue;
        $this->assertEquals($numPages, '1');
    }

    /**
     * Test 4 : DocumentEntity->get() - with invalid xpath expression.
     *
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException
     */
    public function testGeneralGetWithInvaliExpressionShoulThrowException()
    {
        $document = new Document($this->xml);
        // the line below throws exception (the xpath expression is malformed)
        $nodeList = $document->get('/documentInfo@notExistingAttribute');
    }
}
