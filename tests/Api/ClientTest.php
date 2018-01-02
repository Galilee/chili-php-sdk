<?php

namespace Galilee\PPM\Tests\SDK\Chili\Api;

use PHPUnit\Framework\TestCase;
use Galilee\PPM\SDK\Chili\Api\Client;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use ReflectionClass;

/**
 * Class XmlUtilsTest.
 */
class ClientTest extends TestCase
{

    public function testMagicCallToExistingApiMethodShouldReturnXmlString()
    {
        // soapClient response
        $response = new \StdClass();
        $response->MyMethodResult = '<ok />';

        $apiKey = 'abcdef';

        // Params of "MyMethod" method
        $myParams = array('myKey' => 'myValue');

        // "__call" method add apiKey to params :
        $expectedParamsCall = array(
            'apiKey' => $apiKey,
            'myKey' => 'myValue'
        );

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock SoapClient
        $soapClient = $this->getMockBuilder(\SoapClient::class)
            ->disableOriginalConstructor()
            ->setMethods(array('MyMethod'))
            ->getMock();

        $soapClient->expects($this->once())
            ->method('MyMethod')
            ->with($this->equalTo($expectedParamsCall))
            ->willReturn($response);

        // Set soapClient property
        $reflection = new ReflectionClass($client);
        $soapClientProperty = $reflection->getProperty('soapClient');
        $soapClientProperty->setAccessible(true);
        $soapClientProperty->setValue($client, $soapClient);

        // Set apiKey property
        $apiKeyProperty = $reflection->getProperty('apiKey');
        $apiKeyProperty->setAccessible(true);
        $apiKeyProperty->setValue($client, $apiKey);

        // Call API method
        $xmlString = $client->myMethod($myParams);
        $this->assertEquals('<ok />', $xmlString, 'valid xml String');
    }

    public function testMagicCallWithSoapExceptionShouldThrowChiliSoapCallException()
    {
        // soapClient exception :
        $soapException = new \Exception('Something wrong');

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock SoapClient
        $soapClient = $this->getMockBuilder(\SoapClient::class)
            ->disableOriginalConstructor()
            ->setMethods(array('MyMethod'))
            ->getMock();

        $soapClient->expects($this->once())
            ->method('MyMethod')
            ->willThrowException($soapException);

        // Set soapClient property
        $reflection = new ReflectionClass($client);
        $soapClientProperty = $reflection->getProperty('soapClient');
        $soapClientProperty->setAccessible(true);
        $soapClientProperty->setValue($client, $soapClient);

        // Call API method
        $this->expectException(ChiliSoapCallException::class);
        $client->myMethod(array());
    }

}
