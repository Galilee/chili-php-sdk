<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Exception\CreatePdfFailureException;
use Galilee\PPM\SDK\Chili\Exception\CreatePdfTimeOutException;
use Galilee\PPM\SDK\Chili\Helper\XmlUtils;

/**
 * Class Tasks
 * @package Galilee\PPM\SDK\Chili\Service
 */
class Tasks extends AbstractService
{

    const  DELAY_TASK_GET_STATUS_CALL = 3;

    /** Default timeout in synchronous mode (seconds) */
    const  DEFAULT_SYNC_TIMEOUT = 30;

    /**
     * Get the status information for the given task.
     *
     * @param string $taskID
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getStatus($taskID)
    {
        $xmlResponse = $this->client->taskGetStatus(array(
            'taskID' => $taskID
        ));
        return $xmlResponse;
    }

    /**
     * Wait for maximum $timeout seconds while checking if the task has been completed.
     *
     * @param Task $task
     * @param int $syncTimeOut
     * @return Task
     * @throws ChiliSoapCallException
     * @throws CreatePdfTimeOutException
     * @throws CreatePdfFailureException
     */
    public function waitForPdf(Task $task, $syncTimeOut = self::DEFAULT_SYNC_TIMEOUT)
    {
        $timeLimit = $syncTimeOut > 0 ? time() + intval($syncTimeOut) : 0;
        $timeOutReached = false;
        $task->getStatus();

        while (!$task->isFinished() && !$timeOutReached) {

            $task->getStatus();
            if (!$task->isFinished()) {
                if ($timeLimit > 0 && time() > $timeLimit) {
                    $timeOutReached = true;
                }
                sleep(self::DELAY_TASK_GET_STATUS_CALL);
            }
        }

        if ($timeOutReached) {
            throw new CreatePdfTimeOutException();
        }

        if (!$task->isSucceeded()) {
            throw new CreatePdfFailureException($task->getErrorMessage());
        }
        return $task;
    }
}