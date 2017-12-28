<?php


namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Entity;
use Galilee\PPM\SDK\Chili\Client\ResultXml;

/**
 * @method bool isFinished()
 * @method string getResultUrl()
 */
class Task extends AbstractService
{

    protected function getResourceName()
    {
        return '';
    }

    protected function initEntity(ResultXml $resultXml)
    {
        return new Entity\Task($resultXml);
    }

    public function getStatus()
    {
        $params = array(
            "taskID" => $this->getEntity()->getId()
        );
        $result = $this->soapCall->taskGetStatus($params);
        $this->loadXML($result);
        return $this;
    }
}
