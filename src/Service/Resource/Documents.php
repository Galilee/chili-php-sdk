<?php

namespace Galilee\PPM\SDK\Chili\Service\Resource;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity;
use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Entity\UrlInfo;
use Galilee\PPM\SDK\Chili\Entity\Variables;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Service\Tasks;

/**
 * Class Documents
 * @package Galilee\PPM\SDK\Chili\Service\Resource
 */
class Documents extends AbstractResourceService
{

    /**
     * Entity on Load.
     *
     * @param $xmlString
     * @return Entity\Resource\Document
     */
    protected function getEntity($xmlString)
    {
        return new Entity\Resource\Document($this, $xmlString);
    }

    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_DOCUMENTS;
    }

    /**
     * Get Url Editor.
     *
     * @param string $itemID
     * @param bool $allowWorkspaceAdministration
     * @param string $viewPrefsID
     * @param string $workSpaceID
     * @param string $constraintsID
     * @param string $viewerOnly
     * @param bool $forAnonymousUser
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getEditorUrl(
        $itemID,
        $allowWorkspaceAdministration = false,
        $viewPrefsID = '',
        $workSpaceID = '',
        $constraintsID = '',
        $viewerOnly = '',
        $forAnonymousUser = false
    )
    {
        if ($allowWorkspaceAdministration) {
            $this->client->setWorkspaceAdministration(
                array(
                    'allowWorkspaceAdministration' => $allowWorkspaceAdministration
                )
            );
        }

        $params = array(
            'itemID' => $itemID,
            'viewPrefsID' => $viewPrefsID,
            'workSpaceID' => $workSpaceID,
            'constraintsID' => $constraintsID,
            'viewerOnly' => $viewerOnly,
            'forAnonymousUser' => $forAnonymousUser,
        );
        $xmlString = $this->client->documentGetEditorURL($params);
        $urlInfo = new UrlInfo($this, $xmlString);
        $url = $this->client->getConfig()->getProxyUrl()
            ? $this->client->getConfig()->getProxyUrl() . '/' . $urlInfo->getRelativeURL()
            : $urlInfo->getUrl();

        // Remove double slashes
        return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
    }

    /**
     * Get Variable Values.
     *
     * @param string $itemID
     * @return Variables
     * @throws ChiliSoapCallException
     */
    public function getVariableValues($itemID)
    {
        $params = array(
            'itemID' => $itemID,
        );
        $xmlString = $this->client->documentGetVariableValues($params);
        return new Variables($this, $xmlString);
    }

    /**
     * Set Variable Values.
     *
     * @param string $itemID
     * @param string $varXML
     * @return $this
     * @throws ChiliSoapCallException
     */
    public function setVariableValues($itemID, $varXML)
    {
        $params = array(
            'itemID' => $itemID,
            'varXML' => $varXML
        );
        $this->client->documentSetVariableValues($params);
        return $this;
    }

    /**
     * Generate Document PDF.
     *
     * @param string $itemID
     * @param string $settingsXML
     * @param int $taskPriority The priority (1-10) of the task
     * @param bool $async
     * @param int $syncTimeOut
     * @return Task
     * @throws ChiliSoapCallException
     */
    public function createPDF($itemID, $settingsXML, $taskPriority = 5, $async = true, $syncTimeOut = 0)
    {
        if ($taskPriority < 1 || $taskPriority > 10) {
            $taskPriority = 5;
        }
        $params = array(
            'itemID' => $itemID,
            'settingsXML' => $settingsXML,
            'taskPriority' => $taskPriority
        );
        $xmlString = $this->client->documentCreatePDF($params);
        $taskService = new Tasks($this->client);
        $task = new Task($taskService, $xmlString);
        if ($async) {
            $result = $task;
        } else {
            $result = $taskService->waitForPdf($task, $syncTimeOut);
        }
        return $result;
    }

}
