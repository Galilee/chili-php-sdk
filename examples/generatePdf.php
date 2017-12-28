<?php
require 'initAutoloader.php';

use \Galilee\PPM\SDK\Chili;

$yaml = file_get_contents('config/simple.yml');
$config = Chili\Config\ConfigService::fromYaml($yaml);
$cp = new Chili\ChiliPublisher($config);

/** @var Galilee\PPM\SDK\Chili\Service\Document $document */
$document = $cp->getDocument('067cd7e9-ed66-40ca-b326-ef2312ddac11');
$pdfExportSetting = $cp->getPdfExportSetting('b2637267-3c3a-41b0-80fe-6a43dcc5bad6');
$task = $document->createPdf($pdfExportSetting->getEntity()->getXmlDef()->asString());

for ($i = 0; $i < 2; $i++) {
    sleep(1);
    $task->getStatus();
    if ($task->isFinished()) {
        $url = $task->getResultUrl();
        var_dump($url);
        break;
    }
}