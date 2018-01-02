<?php

namespace Galilee\PPM\SDK\Chili\Service\Resource;

use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Entity\Resource\PdfExportSetting;

/**
 * Class PdfExportSetting
 * @package Galilee\PPM\SDK\Chili\Service
 */
class PdfExportSettings extends AbstractResourceService
{

    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_PDF_EXPORT_SETTINGS;
    }

    /**
     * @param $xmlString
     * @return PdfExportSetting
     */
    protected function getEntity($xmlString)
    {
        return new PdfExportSetting($this, $xmlString);
    }
}
