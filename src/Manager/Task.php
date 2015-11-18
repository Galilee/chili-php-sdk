<?php

namespace Galilee\PPM\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Entity\Task as TaskEntity;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;

/**
 * Class Task
 *
 * @package Galilee\PPM\SDK\Chili\Manager
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
        $xmlResponse = $this->soapCall->TaskGetStatus(array(
            'taskID' => $task->getId()
        ));
        $foundTask = new TaskEntity($xmlResponse);

        return $foundTask->getStatus();
    }

    /**
     * Wait for maximum $timeout seconds while checking (every 3 seconds) if the task has been completed
     *
     * @param TaskEntity $task
     * @param int        $timeout - if null or zero then wait until the task is finished
     *
     * @return bool
     *
     * @throws ChiliSoapCallException
     */
    public function waitFor(TaskEntity $task, $timeout = null)
    {
        $timeLimit = $timeout ? time() + intval($timeout) : 0;
        $finished = false;

        while ($finished !== true) {
            if ($timeLimit && time() > $timeLimit) {
                return false;
            }

            $statusInfo = $this->getStatus($task);
            $finished = (
                isset($statusInfo[TaskEntity::STATUS_FINISHED]) &&
                $statusInfo[TaskEntity::STATUS_FINISHED] == true
            );
            // wait for 3 seconds before re-call the Chili webservice
            if (!$finished) {
                sleep(3);
            }
        }

        return $finished;
    }
}
