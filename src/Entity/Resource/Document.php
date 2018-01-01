<?php

namespace Galilee\PPM\SDK\Chili\Entity\Resource;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Entity\UrlInfo;
use Galilee\PPM\SDK\Chili\Entity\Variables;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Helper\XmlUtils;

class Document extends AbstractResourceEntity
{
    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_DOCUMENTS;
    }

    /**
     * @param bool $allowWorkspaceAdministration
     * @param string $viewPrefsID
     * @param string $workSpaceID
     * @param string $constraintsID
     * @param string $viewerOnly
     * @param bool $forAnonymousUser
     * @return mixed
     * @throws ChiliSoapCallException
     */
    public function getEditorUrl(
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
            'itemID' => $this->get('id'),
            'viewPrefsID' => $viewPrefsID,
            'workSpaceID' => $workSpaceID,
            'constraintsID' => $constraintsID,
            'viewerOnly' => $viewerOnly,
            'forAnonymousUser' => $forAnonymousUser,
        );
        $xmlString = $this->client->documentGetEditorURL($params);
        $urlInfo = new UrlInfo($this->client, $xmlString);
        $url = $this->client->getConfig()->getProxyUrl()
            ? $this->client->getConfig()->getProxyUrl() . '/' . $urlInfo->getRelativeURL()
            : $urlInfo->getUrl();

        // Remove double slashes
        return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
    }

    /**
     * Get Variable Values.
     *
     * @return Variables
     * @throws ChiliSoapCallException
     */
    public function getVariableValues()
    {
        $params = array(
            'itemID' => $this->get('id'),
        );
        $xmlString = $this->client->documentGetVariableValues($params);
        return new Variables($this->client, $xmlString);
    }

    /**
     * Set Variable Values.
     *
     * @param Variables $variables
     * @return $this
     * @throws ChiliSoapCallException
     */
    public function setVariableValues(Variables $variables)
    {
        $params = array(
            'itemID' => $this->getId(),
            'varXML' => $variables->getXmlString()
        );
        $this->client->documentSetVariableValues($params);
        return $this;
    }

    /**
     * @param string $settingsXML
     * @param int $taskPriority
     * @return Task
     * @throws ChiliSoapCallException
     */
    public function createPDF($settingsXML, $taskPriority = 7)
    {
        $params = array(
            'itemID' => $this->getId(),
            'settingsXML' => $settingsXML,
            'taskPriority' => $taskPriority
        );
        $xmlString = $this->client->documentCreatePDF($params);
        return new Task($this->client, $xmlString);
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->get('path');
    }

}