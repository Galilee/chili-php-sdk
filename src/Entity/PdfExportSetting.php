<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\ChiliPublisher;


/**
 * Class PdfExportSetting
 * @package Galilee\PPM\SDK\Chili\Service
 *
 */
class PdfExportSetting extends AbstractResourceEntity
{

    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_PDF_EXPORT_SETTINGS;
    }

    public function getServerOutputLocation()
    {
        return $this->get('serverOutputLocation');
    }

    public function setServerOutputLocation($serverOutputLocation)
    {
        /** @var \DomElement $rootNode */
        $rootNode = $this->dom->documentElement;
        $rootNode->setAttribute('serverOutputLocation', $serverOutputLocation);
        return $this;
    }

}
