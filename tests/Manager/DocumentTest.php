<?php

namespace Galilee\PPM\Tests\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Config\ConfigService;
use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Manager\Document as DocumentManager;
use Galilee\PPM\Tests\SDK\Chili\Mock\SoapCall;

/**
 * Class DocumentTest.
 *
 * @backupGlobals disabled
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**@var SoapCall $soapcall */
    private $soapCallMock = null;

    private $config;

    private $apiKey = '111111111111111';

    public function setUp()
    {
        parent::setUp();

        $confType = 'php_array';
        $configArr = [
            'login'       => 'login',
            'password'    => '1234',
            'wsdlUrl'     => 'http://test.wsdlurl.fr/testService?wsdl',
            'environment' => 'test',
            'privateUrl'  => 'http://private.test.fr',
            'publicUrl'   => 'http://public.test.fr',
        ];

        $configService = new ConfigService($confType, $configArr);

        $this->config = $configService->getConfig();
        $mockDirectoryPath = __DIR__ . DIRECTORY_SEPARATOR . 'data';
        $this->soapCallMock = new SoapCall($this->config, $mockDirectoryPath, $this->apiKey);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->soapCallMock = null;
        $this->config = null;
    }

    /**
     * Test 1 : DocumentManager->getDocument(...) returns Document entity
     */
    public function testGetDocumentInfoShouldReturnDocumentEntity()
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('ok');

        $manager = new DocumentManager($this->config, $this->apiKey, false);
        $manager->setSoapCall($this->soapCallMock);

        $result = $manager->getDocument('3728e7ef-adcb-44a3-83f7-d2949edd9cbe');


        $this->assertInstanceOf('Galilee\\PPM\\SDK\\Chili\\Entity\\Document', $result);
        $this->assertEquals($result->getId(), '3728e7ef-adcb-44a3-83f7-d2949edd9cbe');
        $this->assertEquals($result->getName(), 'snippetHotel');

        return $result;
    }

    /**
     * Test 2 : DocumentManager->getPreview(...) returns l'url du preview
     *
     * @depends testGetDocumentInfoShouldReturnDocumentEntity
     */
    public function testGetPreviewShouldReturnThePreviwUrl($document)
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('ok');

        $manager = new DocumentManager($this->config, $this->apiKey, false);
        $manager->setSoapCall($this->soapCallMock);

        $previewUrl = $manager->getPreview($document, 'medium', 1);

        $this->assertNotEmpty($previewUrl);
        $this->assertTrue((filter_var($previewUrl, FILTER_VALIDATE_URL) !== false));
    }

    /**
     * Test 3 : DocumentManager->duplicate(...) returns duplicated Document entity
     *
     * @depends testGetDocumentInfoShouldReturnDocumentEntity
     */
    public function testDuplicateShouldReturnTheNewDocumentEntity($document)
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('okDuplicated');

        $manager = new DocumentManager($this->config, $this->apiKey, false);
        $manager->setSoapCall($this->soapCallMock);

        $duplicated = $manager->duplicate($document, 'copy', '');

        $this->assertInstanceOf('Galilee\\PPM\\SDK\\Chili\\Entity\\Document', $duplicated);
        $this->assertEquals($duplicated->getName(), 'copy');

        return $duplicated;
    }

    /**
     * Test 4 : DocumentManager->buildPdf(...) returns task entity
     *
     * @param $document
     *
     * @depends testGetDocumentInfoShouldReturnDocumentEntity
     */
    public function testBuildPdfShouldReturnTaskEntity($document)
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('ok');

        $manager = new DocumentManager($this->config, $this->apiKey, false);
        $manager->setSoapCall($this->soapCallMock);

        $settingsId = 'e794f36e-a0f3-450d-8ce5-bbc637056d7b';
        $exportProfile = $manager->getDocumentSettings($settingsId);

        $taskInfo = $manager->buildPdf($document, $exportProfile);

        $this->assertInstanceOf('Galilee\\PPM\\SDK\\Chili\\Entity\\Task', $taskInfo);
        $taskId = $taskInfo->get('/task/@id')->item(0)->nodeValue;
        $status = $taskInfo->getStatus();
        $this->assertNotEmpty($taskId);
        $this->assertFalse($status[Task::STATUS_FINISHED]);
    }

    /**
     * Test 5 : DocumentManager->deleteDocument(...) returns true
     *
     * @param $document
     *
     * @depends testDuplicateShouldReturnTheNewDocumentEntity
     */
    public function testDeleteShouldReturnTrue($document)
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('ok');

        $manager = new DocumentManager($this->config, $this->apiKey, false);
        $manager->setSoapCall($this->soapCallMock);

        $result = $manager->deleteDocument($document->getId());

        $this->assertTrue($result);
    }


    /**
     * Test 6 : DocumentManager->getDocument(...)  throws exception
     *
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException
     */
    public function testGetDocumentInfoShoulThrowEntityNotFoundException()
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('notFound');

        $manager = new DocumentManager($this->config, $this->apiKey, false);
        $manager->setSoapCall($this->soapCallMock);

        //throws EntityNotFoundException
        $result = $manager->getDocument('3728e7ef-adcb-44a3-83f7-d2949edd9cbi');
    }
}
