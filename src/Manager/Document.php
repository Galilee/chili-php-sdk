<?php

namespace Galilee\PPM\SDK\Chili\Manager;

use Galilee\PPM\SDK\Chili\Entity\Document as DocumentEntity;
use Galilee\PPM\SDK\Chili\Entity\ExportProfile;
use Galilee\PPM\SDK\Chili\Entity\Task;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class Document - manages document entities
 */
class Document extends AbstractManager
{
    /**
     * Get a Chili document by ID
     *
     * @param string $id
     * @param bool $lazy - if true sets the property $id of the Document entity
     *
     * @return DocumentEntity $document
     */
    public function getDocument($id, $lazy = false)
    {
        //check the given document id
        $result = $this->searchResourceById($id, DocumentEntity::RESOURCE_NAME);

        if ($result) {
            $xmlResponse = $this->soapCall->DocumentGetInfo([
                'itemID' => $id,
                'extended' => 0,
            ]);

            return new DocumentEntity($xmlResponse, $lazy);
        }

        return null;
    }

    /**
     * Get the preview URL of a Chili document
     *
     * @param DocumentEntity $document
     * @param string $type (thumbnail | medium | full | swf)
     * @param int $pageNum
     *
     * @return string $url
     */
    public function getPreview(DocumentEntity $document, $type, $pageNum)
    {
        $xmlResponse = $this->soapCall->ResourceItemGetURL([
            'resourceName' => DocumentEntity::RESOURCE_NAME,
            'itemID' => $document->getId(),
            'type' => $type,
            'pageNum' => $pageNum,
        ]);

        $nodeList = Parser::get($xmlResponse, '/urlInfo[1]/@url');
        if($nodeList->length == 1){
            return $nodeList->item(0)->nodeValue;
        }

        return null;
    }

    /**
     * Duplicate a Chili document
     *
     * @param DocumentEntity $document
     *
     * @return DocumentEntity $duplicatedDoc
     */
    public function duplicate(DocumentEntity $document, $newName = 'copy', $folderPath = '')
    {
        $xmlResponse = $this->soapCall->ResourceItemCopy([
            'resourceName' => DocumentEntity::RESOURCE_NAME,
            'itemID' => $document->getId(),
            'newName' => $newName,
            'folderPath' => $folderPath,
        ]);
        $nodeList = Parser::get($xmlResponse, '/item[1]/@id');
        if($nodeList->length == 1){
            $duplicatedDocId = $nodeList->item(0)->nodeValue;

            return $this->getDocument($duplicatedDocId);
        }

        return null;
    }

    /**
     * Generate a PDF from a Chili document and return the task information
     *
     * @param DocumentEntity $document
     * @param ExportProfile $exportProfile
     * @param int $taskPriority (1-10)
     *
     * @return Task $task
     */
    public function buildPdf(DocumentEntity $document, ExportProfile $exportProfile, $taskPriority = 7)
    {
        $xmlResponse = $this->soapCall->DocumentCreatePDF([
            'itemID' => $document->getId(),
            'settingsXml' => $exportProfile->getXmlDef(),
            'taskPriority' => $taskPriority,
        ]);

        return new Task($xmlResponse);
    }

    /**
     * Get Chili document settings for the PDF export
     *
     * @param string $id
     *
     * @return ExportProfile
     */
    public function getDocumentSettings($id)
    {
        //check the given export profile id
        $result = $this->searchResourceById($id, ExportProfile::RESOURCE_NAME);
        if ($result) {
            $xmlResponse = $this->soapCall->ResourceItemGetDefinitionXML([
                'resourceName' => ExportProfile::RESOURCE_NAME,
                'itemID' => $id,
            ]);

            return new ExportProfile($xmlResponse);
        }

        return null;
    }


}
