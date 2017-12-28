<?php

namespace Galilee\PPM\SDK\Chili\Service;

use Galilee\PPM\SDK\Chili\Entity\PdfExportSetting as PdfExportSettingEntity;
use Galilee\PPM\SDK\Chili\ChiliPublisher;
use Galilee\PPM\SDK\Chili\Client\ResultXml;

class PdfExportSetting extends AbstractService
{

    protected function getResourceName()
    {
        return ChiliPublisher::RESOURCE_NAME_PDF_EXPORT_SETTINGS;
    }

    protected function initEntity(ResultXml $resultXml)
    {
        return new PdfExportSettingEntity($resultXml);
    }
}
