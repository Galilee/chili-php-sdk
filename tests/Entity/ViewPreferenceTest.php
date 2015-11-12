<?php

namespace Galilee\PPM\Tests\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Entity\ViewPreference;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class ViewPreferenceTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Entity
 * @backupGlobals disabled
 */
class ViewPreferenceTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        parent::setUp();

        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'viewPreference.xml'));
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
     * Test 1 : ViewPreferenceEntity->getId() returns view preference id
     */
    public function testGetIdShouldReturnViewPreferenceId()
    {
        $viewPref = new ViewPreference($this->xml);
        $id = $viewPref->getId();
        $this->assertEquals($id, '9a672df2-4d3d-44cb-bf97-b5ddc0dfc5a0');
    }
}
