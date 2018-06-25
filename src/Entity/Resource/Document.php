<?php

namespace Galilee\PPM\SDK\Chili\Entity\Resource;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Entity\Variables;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Service\Resource\Documents;

class Document extends AbstractResourceEntity
{

    /** @var  Documents */
    protected $service;


    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_DOCUMENTS;
    }

    /**
     * Get path attribute.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->get('path');
    }



    /**
     * Get Url Editor.
     *
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
        $allowWorkspaceAdministration = false,
        $viewPrefsID = '',
        $workSpaceID = '',
        $constraintsID = '',
        $viewerOnly = '',
        $forAnonymousUser = false
    )
    {
        return $this->service->getEditorUrl(
            $this->getId(),
            $allowWorkspaceAdministration ,
            $viewPrefsID,
            $workSpaceID,
            $constraintsID,
            $viewerOnly,
            $forAnonymousUser
        );
    }
	
	
    /**
     * Get Url HTML Editor.
     *
     * @param bool $allowWorkspaceAdministration
     * @param string $viewPrefsID
     * @param string $workSpaceID
     * @param string $constraintsID
     * @param string $viewerOnly
     * @param bool $forAnonymousUser
     * @return string
     * @throws ChiliSoapCallException
     */
    public function getHTMLEditorUrl(
        $allowWorkspaceAdministration = false,
        $viewPrefsID = '',
        $workSpaceID = '',
        $constraintsID = '',
        $viewerOnly = '',
        $forAnonymousUser = false
    )
    {
        return $this->service->getEditorUrl(
            $this->getId(),
            $allowWorkspaceAdministration ,
            $viewPrefsID,
            $workSpaceID,
            $constraintsID,
            $viewerOnly,
            $forAnonymousUser
        );
    }


    /**
     * Get Variable Values.
     *
     * @return Variables
     * @throws ChiliSoapCallException
     */
    public function getVariableValues()
    {
        return $this->service->getVariableValues($this->getId());
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
        $this->service->setVariableValues($this->getId(), $variables->getXmlString());
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
        return $this->service->createPDF($this->getId(), $settingsXML, $taskPriority);
    }

}