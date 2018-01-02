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

$documentEntity = $chiliPublisher
    ->documents()
    ->load('067cd7e9-ed66-40ca-b326-ef2312ddac11');

// Editor url
$urlEditor = $documentEntity->getEditorUrl();

// Document Variables
$variableValues = $documentEntity->getVariableValues();

// Document Preview
$url = $documentEntity->getThumbnailPreview();


// Search Document By name
$result = $chiliPublisher->documents()->search('Galilee');
$count = $result->getCount();

// Search Document By Id
$result = $chiliPublisher->documents()->searchByIDs(
    array(
        '067cd7e9-ed66-40ca-b326-ef2312ddac11',
        '9e3448c7-7d39-415d-8116-1929850769d8'
    )
);
$count = $result->getCount();

$isExists = $chiliPublisher->documents()->isExists('067cd7e9-ed66-40ca-b326-ef2312ddac11');
