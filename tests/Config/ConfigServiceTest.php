<?php

namespace Galilee\PPM\Tests\SDK\Chili\Config;

use Galilee\PPM\SDK\Chili\Config\ConfigService;
/**
 * Class ConfigServiceTest.
 *
 * @backupGlobals disabled
 */
class ConfigServiceTest extends \PHPUnit_Framework_TestCase
{

    /*public function setUp(){
        parent::setUp();
    }*/

    public function testPhpArrayConfigShouldReturnConfigObject()
    {
        $type = 'php_array';
        $configArr = [
            'login'       => 'login',
            'password'    => '1234',
            'wsdlUrl'     => 'http://test.wsdlurl.fr/testService?wsdl',
            'environment' => 'test',
            'privateUrl'  => 'http://private.test.fr',
            'publicUrl'   => 'http://public.test.fr',
        ];

        $configService = new ConfigService($type, $configArr);
        $config = $configService->getConfig();

        $this->assertInstanceOf('Galilee\PPM\SDK\Chili\Config\Config', $config);
        $this->assertEquals($config->getLogin(), $configArr['login']);
        $this->assertEquals($config->getWsdlUrl(), $configArr['wsdlUrl']);
        $this->assertEquals($config->getEnvironment(), $configArr['environment']);
        $this->assertEquals($config->getPassword(), $configArr['password']);
        $this->assertEquals($config->getPrivateUrl(), $configArr['privateUrl']);
        $this->assertEquals($config->getPublicUrl(), $configArr['publicUrl']);
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
     */
    public function testPhpArrayConfigWithMissingParameterShouldThrowException()
    {
        $type = 'php_array';
        $configArr = [
            'password'    => '1234',
            'wsdlUrl'     => 'http://test.wsdlurl.fr/testService?wsdl',
            'environment' => 'test',
            'privateUrl'  => 'http://private.test.fr',
            'publicUrl'   => 'http://public.test.fr',
        ];

        $configService = new ConfigService($type, $configArr);
        $config = $configService->getConfig();
    }

    public function testYamlConfigShouldReturnConfigObject()
    {
        $yamlConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple.yml');

        $configService = new ConfigService('yaml', $yamlConf);
        $config = $configService->getConfig();

        $this->assertInstanceOf('Galilee\PPM\SDK\Chili\Config\Config', $config);
        $this->assertEquals($config->getLogin(), 'test');
        $this->assertEquals($config->getEnvironment(), 'test');
        $this->assertEquals($config->getPassword(), 'test@pwd');
        $this->assertEquals($config->getPrivateUrl(), 'http://private.test.fr');
        $this->assertEquals($config->getPublicUrl(), 'http://public.test.fr');

    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidYamlException
     */
    public function testYamlInvalidConfigShouldThrowException()
    {
        $yamlConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple_invalid.yml');

        $configService = new ConfigService('yaml', $yamlConf);
        $config = $configService->getConfig();
    }


    public function testXmlConfigShouldReturnConfigObject()
    {
        $xmlConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple.xml');

        $configService = new ConfigService('xml', $xmlConf);
        $config = $configService->getConfig();

        $this->assertInstanceOf('Galilee\PPM\SDK\Chili\Config\Config', $config);
        $this->assertEquals($config->getLogin(), 'test');
        $this->assertEquals($config->getEnvironment(), 'test');
        $this->assertEquals($config->getPassword(), 'test@pwd');
        $this->assertEquals($config->getPrivateUrl(), 'http://private.test.fr');
        $this->assertEquals($config->getPublicUrl(), 'http://public.test.fr');
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidXmlException
     */
    public function testIvalidXmlConfigShouldThrowInvalidXmlException()
    {
        $xmlConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple_invalid.xml');

        $configService = new ConfigService('xml', $xmlConf);
        $config = $configService->getConfig();
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
     */
    public function testIvalidXmlConfigShouldThrowInvalidConfigurationException()
    {
        $xmlConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple_invalid_property.xml');

        $configService = new ConfigService('xml', $xmlConf);
        $config = $configService->getConfig();
    }

    public function testJsonConfigShouldReturnConfigObject()
    {
        $jsonConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple.json');

        $configService = new ConfigService('json', $jsonConf);
        $config = $configService->getConfig();

        $this->assertInstanceOf('Galilee\PPM\SDK\Chili\Config\Config', $config);
        $this->assertEquals($config->getLogin(), 'test');
        $this->assertEquals($config->getEnvironment(), 'test');
        $this->assertEquals($config->getPassword(), 'test@pwd');
        $this->assertEquals($config->getPrivateUrl(), 'http://private.test.fr');
        $this->assertEquals($config->getPublicUrl(), 'http://public.test.fr');
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidJsonException
     */
    public function testIvalidJsonConfigShouldThrowInvalidJsonException()
    {
        $jsonConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple_invalid.json');

        $configService = new ConfigService('json', $jsonConf);
        $config = $configService->getConfig();
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
     */
    public function testIvalidJsonConfigShouldThrowInvalidConfigurationException()
    {
        $jsonConf = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR.'simple_invalid_property.json');

        $configService = new ConfigService('json', $jsonConf);
        $config = $configService->getConfig();
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
     */
    public function testInvalidTypeConfigurationShouldThrowException(){
        $conf = array('test' => 'test');
        $confType = 'dummy';

        $configService = new ConfigService($confType, $conf);
        $config = $configService->getConfig();
    }
}
