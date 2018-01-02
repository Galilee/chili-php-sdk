<?php

namespace Galilee\PPM\SDK\Chili\Entity;

class UrlInfo extends AbstractEntity
{

    public function getItemID()
    {
        return $this->get('itemID');
    }

    public function getItemName()
    {
        return $this->get('itemName');
    }

    public function getItemFileName()
    {
        return $this->get('itemFileName');
    }

    public function getRelativeURL()
    {
        return $this->get('relativeURL');
    }

    public function getUrl()
    {
        return $this->get('url');
    }

    public function getPreviewExists()
    {
        return strtolower($this->get('previewExists')) == 'true';
    }

    public function getPreviewModificationDate()
    {
        return $this->get('previewModificationDate');
    }

    public function getPreviewFileSize()
    {
        return $this->get('previewFileSize');
    }
}
