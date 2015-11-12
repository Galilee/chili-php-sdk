<?php

namespace Galilee\PPM\Tests\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Entity\Workspace;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class WorkspaceTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Entity
 * @backupGlobals disabled
 */
class WorkspaceTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        parent::setUp();

        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'workspace.xml'));
        $info = $xml->getElementsByTagName('ResourceSearchByIDsResult')->item(0)->textContent;

        $result = Parser::get($info, '//searchresults/item');
        $item = $result->item(0);
        $this->xml = $item->ownerDocument->saveXML($item);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->xml = null;
    }


    /**
     * Test 1 : WorkspaceEntity->getId() returns view preference id
     */
    public function testGetIdShouldReturnWorkspaceId()
    {
        $workspace = new Workspace($this->xml);
        $id = $workspace->getId();
        $this->assertEquals($id, '5e57eb29-51e9-4f0d-adfd-531e385cf4ff');
    }
}
