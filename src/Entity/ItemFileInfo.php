<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Api\Client;

/**
 * Resulte from chili methods :
 *  - ResourceSearch
 *  - ResourceSearchByIDs
 *  - ResourceItemCopy
 *
 * Class ItemFileInfo
 * @package Galilee\PPM\SDK\Chili\Entity
 */
class ItemFileInfo extends AbstractEntity
{
    /** @var \DOMElement */
    protected $fileInfoNode;

    public function __construct(Client $client, $xmlString)
    {
        parent::__construct($client, $xmlString);
        $this->fileInfoNode = $this->dom->documentElement
            ->getElementsByTagName('fileInfo')
            ->item(0);
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function getRelativePath()
    {
        return $this->get('relativePath');
    }

    public function getHasPreviewErrors()
    {
        return $this->getBoolean('hasPreviewErrors');
    }


    // FileInfo Node attributes


    public function getFileIndexed()
    {
        return $this->get('fileIndexed', $this->fileInfoNode);
    }

    public function getNumPages()
    {
        return $this->get('numPages', $this->fileInfoNode);
    }

    public function getResolution()
    {
        return $this->get('resolution', $this->fileInfoNode);
    }

    public function getWidth()
    {
        return $this->get('width', $this->fileInfoNode);
    }

    public function getHeight()
    {
        return $this->get('height', $this->fileInfoNode);
    }

    public function getFileSize()
    {
        return $this->get('fileSize', $this->fileInfoNode);
    }

    public function getPreviewModificationDate()
    {
        return $this->get('previewModificationDate', $this->fileInfoNode);
    }


}
