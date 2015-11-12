<?php

namespace Galilee\PPM\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Entity\Task as TaskEntity;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;

/**
 * Class Task.
 */
class Task extends AbstractManager
{
    /**
     * Get the status information for the given task.
     *
     * @param TaskEntity $task
     *
     * @return array
     *
     * @throws ChiliSoapCallException
     */
    public function getStatus(TaskEntity $task)
    {
        $xmlResponse = $this->soapCall->TaskGetStatus([
            'taskID' => $task->getId()
        ]);
        $foundTask = new TaskEntity($xmlResponse);

        return $foundTask->getStatus();
    }

    /**
     * @param TaskEntity $task
     * @param int        $timeout
     */
    /*public function waitFor(TaskEntity $task, $timeout = null)
    {
        // todo ?
    }*/
}
