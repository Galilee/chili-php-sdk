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

    public function setUp(){
        parent::setUp();
    }

    public function testPhpArrayConfigShouldReturnConfigObject()
    {
        $type = 'php_array';
        $config_arr = [
            'login'       => 'login',
            'password'    => '1234',
            'wsdlUrl'     => 'http://test.wsdlurl.fr/testService?wsdl',
            'environment' => 'test',
            'privateUrl'  => 'http://private.test.fr',
            'publicUrl'   => 'http://public.test.fr',
        ];

        $configService = new ConfigService($type, $config_arr);
        $config = $configService->getConfig();

        $this->assertInstanceOf('Galilee\PPM\SDK\Chili\Config\Config', $config);
        $this->assertEquals($config->getLogin(), $config_arr['login']);
        $this->assertEquals($config->getWsdlUrl(), $config_arr['wsdlUrl']);
        $this->assertEquals($config->getEnvironment(), $config_arr['environment']);
        $this->assertEquals($config->getPassword(), $config_arr['password']);
        $this->assertEquals($config->getPrivateUrl(), $config_arr['privateUrl']);
        $this->assertEquals($config->getPublicUrl(), $config_arr['publicUrl']);
    }

    /**
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
     */
    public function testPhpArrayConfigWithMissingParameterShouldThrowException()
    {
        $type = 'php_array';
        $config_arr = [
            'password'    => '1234',
            'wsdlUrl'     => 'http://test.wsdlurl.fr/testService?wsdl',
            'environment' => 'test',
            'privateUrl'  => 'http://private.test.fr',
            'publicUrl'   => 'http://public.test.fr',
        ];

        $configService = new ConfigService($type, $config_arr);
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
     * @expectedException \Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException
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

    public function testIvalidXmlConfigShouldThrowException()
    {
        //todo
    }

    public function testJsonConfigShouldReturnConfigObject()
    {
        //todo
    }

    public function testIvalidJsonConfigShouldThrowException()
    {
        //todo
    }
}
