<?php

namespace Galilee\PPM\Tests\SDK\Chili\Entity;
use Galilee\PPM\SDK\Chili\Entity\DocumentConstraint;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class DocumentConstraintTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Entity
 * @backupGlobals disabled
 */
class DocumentConstraintTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp(){
        parent::setUp();

        $xml = new \DOMDocument();
        $xml->loadXML( file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'documentConstraint.xml'));
        $info = $xml->getElementsByTagName('ResourceSearchByIDsResult')->item(0)->textContent;

        $result = Parser::get($info, '//searchresults/item');
        $item = $result->item(0);
        $this->xml = $item->ownerDocument->saveXML($item);
    }

    public function tearDown(){
        parent::tearDown();

        $this->xml = null;
    }


    /**
     * Test 1 : DocumentConstraintEntity->getId() returns document constraint id
     */
    public function testGetIdShouldReturnStringDocumentId()
    {
        $documentConstr = new DocumentConstraint($this->xml);
        $id = $documentConstr->getId();
        $this->assertEquals($id, '1e778fb7-ed93-4ca2-aadf-ce5339868fe5');
    }
}
