<?php

namespace Galilee\PPM\Tests\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Config\ConfigService;
use Galilee\PPM\SDK\Chili\Manager\Document as DocumentManager;
use Galilee\PPM\SDK\Chili\Manager\Editor;
use Galilee\PPM\Tests\SDK\Chili\Mock\SoapCall;

/**
 * Class EditorTest.
 *
 *
 * @backupGlobals disabled
 */
class EditorTest extends \PHPUnit_Framework_TestCase
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
            'login' => 'login',
            'password' => '1234',
            'wsdlUrl' => 'http://test.wsdlurl.fr/testService?wsdl',
            'environment' => 'test',
            'privateUrl' => 'http://private.test.fr',
            'publicUrl' => 'http://public.test.fr',
        ];

        $configService = new ConfigService($confType, $configArr);
        $mockDirectoryPath = __DIR__.DIRECTORY_SEPARATOR.'data';

        $this->config = $configService->getConfig();
        $this->soapCallMock = new SoapCall($this->config, $mockDirectoryPath, $this->apiKey);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->soapCallMock = null;
        $this->config = null;
    }

    /**
     * Test 1 : EditorManager->getWokspace(id) returns Workspace entity.
     */
    public function testGetWorkspaceShouldReturnWorkspaceEntity()
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('workspaceOk');

        $editorManager = new Editor($this->config, $this->apiKey, false);
        $editorManager->setSoapCall($this->soapCallMock);
        $workspace = $editorManager->getWorkspace('5e57eb29-51e9-4f0d-adfd-531e385cf4ff');

        $this->assertInstanceOf('Galilee\\PPM\\SDK\\Chili\\Entity\\Workspace', $workspace);
        $this->assertNotEmpty($workspace->getId());
        $this->assertEquals($workspace->getId(), '5e57eb29-51e9-4f0d-adfd-531e385cf4ff');

        return $workspace;
    }

    /**
     * Test 2 : EditorManager->getViewPreference(id) returns ViewPreference entity.
     */
    public function testGetViewPreferenceShouldReturnViewPreferenceEntity()
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('viewPrefOk');

        $editorManager = new Editor($this->config, $this->apiKey, false);
        $editorManager->setSoapCall($this->soapCallMock);
        $viewPreference = $editorManager->getViewPreference('9a672df2-4d3d-44cb-bf97-b5ddc0dfc5a0');

        $this->assertInstanceOf('Galilee\\PPM\\SDK\\Chili\\Entity\\ViewPreference', $viewPreference);
        $this->assertNotEmpty($viewPreference->getId());
        $this->assertEquals($viewPreference->getId(), '9a672df2-4d3d-44cb-bf97-b5ddc0dfc5a0');

        return $viewPreference;
    }

    /**
     * Test 3 : EditorManager->getDocumentConstraint(id) returns DocumentConstraint entity.
     */
    public function testGetDocumentConstraintShouldReturnDocumentConstraintEntity()
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('docConstraintOk');

        $editorManager = new Editor($this->config, $this->apiKey, false);
        $editorManager->setSoapCall($this->soapCallMock);
        $docConstraint = $editorManager->getDocumentConstraint('1e778fb7-ed93-4ca2-aadf-ce5339868fe5');

        $this->assertInstanceOf('Galilee\\PPM\\SDK\\Chili\\Entity\\DocumentConstraint', $docConstraint);
        $this->assertNotEmpty($docConstraint->getId());
        $this->assertEquals($docConstraint->getId(), '1e778fb7-ed93-4ca2-aadf-ce5339868fe5');

        return $docConstraint;
    }

    /**
     * Test 4 : EditorManager->getEditor(...) returns Chili Editor.
     *
     * @depends testGetWorkspaceShouldReturnWorkspaceEntity
     * @depends testGetViewPreferenceShouldReturnViewPreferenceEntity
     * @depends testGetDocumentConstraintShouldReturnDocumentConstraintEntity
     */
    public function testGetEditorShouldReturnChiliEditorURL($workspace, $viewPref, $constraint)
    {
        // Set scenario name
        $this->soapCallMock->setScenarioName('ok');

        $docManager = new DocumentManager($this->config, $this->apiKey, false);
        $docManager->setSoapCall($this->soapCallMock);
        $document = $docManager->getDocument('3728e7ef-adcb-44a3-83f7-d2949edd9cbe');

        $editorManager = new Editor($this->config, $this->apiKey, false);
        $editorManager->setSoapCall($this->soapCallMock);

        $editorUrl = $editorManager->getEditor($document, $workspace, $viewPref, $constraint);

        $this->assertNotEmpty($editorUrl);
    }
}
