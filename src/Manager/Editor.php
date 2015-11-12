<?php

namespace Galilee\PPM\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Entity\Document as DocumentEntity;
use Galilee\PPM\SDK\Chili\Entity\DocumentConstraint;
use Galilee\PPM\SDK\Chili\Entity\ViewPreference;
use Galilee\PPM\SDK\Chili\Entity\Workspace;
use Galilee\PPM\SDK\Chili\Exception\ChiliSoapCallException;
use Galilee\PPM\SDK\Chili\Exception\EntityNotFoundException;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class Editor.
 */
class Editor extends AbstractManager
{
    /**
     * Get a Chili workspace by ID.
     *
     * @param string $id
     *
     * @return Workspace|null
     *
     * @throws EntityNotFoundException
     */
    public function getWorkspace($id)
    {
        $result = $this->searchResourceById($id, Workspace::RESOURCE_NAME);

        return new Workspace($result);
    }

    /**
     * Get a Chili ViewPreference by ID.
     *
     * @param string $id
     *
     * @return ViewPreference|null
     *
     * @throws EntityNotFoundException
     */
    public function getViewPreference($id)
    {
        $result = $this->searchResourceById($id, ViewPreference::RESOURCE_NAME);

        return new ViewPreference($result);
    }

    /**
     * Get a Chili DocumentConstraint by ID.
     *
     * @param string $id
     *
     * @return DocumentConstraint|null
     *
     * @throws EntityNotFoundException
     */
    public function getDocumentConstraint($id)
    {
        $result = $this->searchResourceById($id, DocumentConstraint::RESOURCE_NAME);

        return new DocumentConstraint($result);
    }

    /**
     * Get the relative URL of a Chili Editor.
     *
     * @param DocumentEntity     $document
     * @param Workspace          $workspace
     * @param ViewPreference     $viewPreference
     * @param DocumentConstraint $documentConstraint
     *
     * @return string|null
     *
     * @throws ChiliSoapCallException
     */
    public function getEditor(DocumentEntity $document, Workspace $workspace, ViewPreference $viewPreference, DocumentConstraint $constraint /*, $language*/)
    {
        $xmlResponse = $this->soapCall->DocumentGetEditorURL([
            'itemID' => $document->getId(),
            'workSpaceID' => $workspace->getId(),
            'viewPrefsID' => $viewPreference->getId(),
            'constraintsID' => $constraint->getId(),
            'viewerOnly' => false,
            'forAnonymousUser' => false,
        ]);

        $nodeList = Parser::get($xmlResponse, '//urlInfo/@relativeURL');
        if ($nodeList->length == 1) {
            return $nodeList->item(0)->nodeValue;
        }

        return;
    }
}
