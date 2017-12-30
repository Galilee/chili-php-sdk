<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity\PdfExportSetting;
use Galilee\PPM\SDK\Chili\Entity;

class Document extends AbstractResourceEntity
{
    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_DOCUMENTS;
    }

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
            ? $this->client->getConfig()->getProxyUrl() . $urlInfo->getRelativeURL()
            : $urlInfo->getUrl();

        // Remove double slashes
        return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
    }

    /**
     * Get Variable Values.
     *
     * @return string
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
     * @param string $xmlString
     * @return string
     */
    public function setVariableValues($xmlString)
    {
        $params = array(
            'itemID' => $this->getId(),
            'varXML' => $xmlString
        );
        return $this->client->documentSetVariableValues($params);
    }

    /**
     * @param PdfExportSetting $pdfExportSetting
     * @param int $taskPriority
     * @return Task
     */
    public function createPDF(PdfExportSetting $pdfExportSetting, $taskPriority = 7)
    {
        $params = array(
            'itemID' => $this->getId(),
            'settingsXML' => $pdfExportSetting->getXmlString(),
            'taskPriority' => $taskPriority
        );
        $xmlString = $this->client->documentCreatePDF($params);
        return new Entity\Task($this->client, $xmlString);
    }

}