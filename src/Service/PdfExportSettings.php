<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity;

/**
 * Class PdfExportSetting
 * @package Galilee\PPM\SDK\Chili\Service
 *
 */
class PdfExportSettings extends AbstractService
{

    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_PDF_EXPORT_SETTINGS;
    }

    /**
     * @param $xmlString
     * @return Entity\PdfExportSetting
     */
    protected function getEntity($xmlString)
    {
        return new Entity\PdfExportSetting($this->client, $xmlString);
    }
}
