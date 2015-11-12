<?php

namespace Galilee\PPM\Tests\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class TaskTest
 *
 * @package Galilee\PPM\Tests\SDK\Chili\Entity
 * @backupGlobals disabled
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        parent::setUp();

        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'task.xml'));
        $info = $xml->getElementsByTagName('TaskGetStatusResult')->item(0)->textContent;

        $this->xml = $info;
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->xml = null;
    }


    /**
     * Test 1 : TaskEntity->getId() returns task id
     */
    public function testGetIdShouldReturnStringTaskId()
    {
        $task = new Task($this->xml);
        $id = $task->getId();
        $this->assertEquals($id, 'f1a681c5-5078-45eb-8c05-09c321e9025e');
    }

    /**
     * Test 2 : TaskEntity->getStatus() returns task status info
     */
    public function testGetStatusShouldReturnStatusInfo()
    {
        $task = new Task($this->xml);
        $status = $task->getStatus();

        $this->assertArrayHasKey(Task::STATUS_SUCCEEDED, $status);
        $this->assertArrayHasKey(Task::STATUS_FINISHED, $status);
        $this->assertArrayHasKey(Task::STATUS_ERROR_MESSAGE, $status);
        $this->assertArrayHasKey(Task::STATUS_ERROR_STACK, $status);
        $this->assertArrayHasKey(Task::STATUS_PATH, $status);
        $this->assertArrayHasKey(Task::STATUS_RELATIVE_URL, $status);
        $this->assertArrayHasKey(Task::STATUS_URL, $status);

        $this->assertTrue($status[Task::STATUS_SUCCEEDED]);
        $this->assertTrue($status[Task::STATUS_FINISHED]);
    }
}
