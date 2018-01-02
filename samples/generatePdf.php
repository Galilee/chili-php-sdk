<?php
require 'initAutoloader.php';

use \Galilee\PPM\SDK\Chili\ChiliPublisher;

$chiliPublisher = new ChiliPublisher(
    array(
        'username' => 'john',
        'password' => 'doe',
        'url' => 'http://my-chili.fr',
        'environment' => 'my-environment',
        'proxyUrl' => 'http://my-website/chilieditor'
    )
);

/** @var Galilee\PPM\SDK\Chili\Entity\Document $documentEntity */
$documentEntity = $chiliPublisher
    ->documents()
    ->load('067cd7e9-ed66-40ca-b326-ef2312ddac11');

/** @var \Galilee\PPM\SDK\Chili\Entity\PdfExportSetting $pdfExportSettingEntity */
$pdfExportSettingEntity = $chiliPublisher
    ->pdfExportSettings()
    ->load('b2637267-3c3a-41b0-80fe-6a43dcc5bad6');

$path = $pdfExportSettingEntity->getServerOutputLocation();

$task = $documentEntity->createPdf($pdfExportSettingEntity);

for ($i = 0; $i < 20; $i++) {
    sleep(1);
    $task->getStatus();
    if ($task->isFinished()) {
        if ($task->isSucceeded()) {
            $url = $task->getResultUrl();
        } else {
            $errorMessage = $task->getErrorMessage();
        }
        break;
    }
}