<?php

namespace Galilee\PPM\Tests\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Entity\ExportProfile;

/**
 * Class ExportProfileTest.
 *
 * @backupGlobals disabled
 */
class ExportProfileTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        parent::setUp();

        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'exportProfile.xml'));
        $exportProfileInfo = $xml->getElementsByTagName('ResourceItemGetDefinitionXMLResult')->item(0);

        $this->xml = $exportProfileInfo->textContent;
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->xml = null;
    }

    /**
     * Test 1 : ExportProfileEntity->getName() returns ExportProfile name.
     */
    public function testGetNameShouldReturnStringExportProfileName()
    {
        $exportProfile = new ExportProfile($this->xml);
        $name = $exportProfile->getName();
        $this->assertEquals($name, 'pdf-test');
    }

    /**
     * Test 2 : ExportProfileEntity->getId() returns ExportProfile ID.
     */
    public function testGetIdShouldReturnStringExportProfileId()
    {
        $exportProfile = new ExportProfile($this->xml);
        $id = $exportProfile->getId();
        $this->assertEquals($id, 'e794f36e-a0f3-450d-8ce5-bbc637056d7b');
    }

    /**
     * Test 3 : ExportProfileEntity->get(...) returns custom attribute value.
     */
    public function testGeneralGetShouldReturnCustomAttribute()
    {
        $exportProfile = new ExportProfile($this->xml);
        $nodeList = $exportProfile->get('/item/@imageQuality');
        $imgQuality = $nodeList->item(0)->nodeValue;
        $this->assertEquals($imgQuality, 'original');
    }
}
