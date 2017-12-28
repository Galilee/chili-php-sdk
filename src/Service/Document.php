<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Entity\Document as DocumentEntity;
use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Client\ResultXml;

/**
 * Class Document - manages document entities
 */
class Document extends AbstractService
{

    protected $proxyUrl = '';

    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_DOCUMENTS;
    }

    protected function initEntity(ResultXml $resultXml)
    {
        return new DocumentEntity($resultXml);
    }

    /**
     * @return string
     */
    public function getProxyUrl()
    {
        return $this->proxyUrl;
    }

    /**
     * @param string $proxyUrl
     * @return Document
     */
    public function setProxyUrl($proxyUrl)
    {
        $this->proxyUrl = $proxyUrl;
        return $this;
    }


    /**
     * Get Variable Values.
     *
     * @return ResultXml
     */
    public function getVariableValues()
    {
        $params = array(
            "itemID" => $this->getEntity()->getId(),
        );
        return $this->soapCall->documentGetVariableValues($params);
    }

    /**
     * Set Variable Values.
     *
     * @param ResultXml $resultXml
     * @return ResultXml
     */
    public function setVariableValues(ResultXml $resultXml)
    {
        $varXML = $resultXml->asString();
        $params = array(
            "itemID" => $this->getEntity()->getId(),
            "varXML" => $varXML
        );
        return $this->soapCall->documentSetVariableValues($params);
    }

    public function createPdf($settingsXML, $taskPriority = 7)
    {
        $params = array(
            "itemID" => $this->getEntity()->getId(),
            "settingsXML" => $settingsXML,
            "taskPriority" => $taskPriority
        );
        $result = $this->soapCall->documentCreatePDF($params);
        $task = new Task($this->soapCall);
        $task->loadXML($result);
        return $task;
    }
}
