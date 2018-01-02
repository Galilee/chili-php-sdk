<?php

require 'initAutoloader.php';

use \Galilee\PPM\SDK\Chili\ChiliPublisher;


$chiliPublisher = new ChiliPublisher(
    array(
        'username' => 'form1',
        'password' => 'form1',
        'url' => 'http://chili-demo.galilee.fr',
        'environment' => 'galilee',
        'proxyUrl' => 'http://my-chili-app/chilieditor'
    )
);

/** @var \Galilee\PPM\SDK\Chili\Entity\Document $documentEntity */
$documentEntity = $chiliPublisher
    ->documents()
    ->load('067cd7e9-ed66-40ca-b326-ef2312ddac11');

var_dump($documentEntity->getEditorUrl());

// Preview
$url = $documentEntity->getThumbnailPreview();
echo '<img src="' . $url . '" />';


// Search
$result = $chiliPublisher->documents()->search('Galilee');
var_dump($result->getCount());
echo $result;



$result = $chiliPublisher->documents()->searchByIDs(
    array(
        '067cd7e9-ed66-40ca-b326-ef2312ddac11',
        '9e3448c7-7d39-415d-8116-1929850769d8'
    )
);
var_dump($result->getCount());
echo $result;



$isExists = $chiliPublisher->documents()->isExists('067cd7e9-ed66-40ca-b326-ef2312ddac11');
var_dump($isExists);

$isExists = $chiliPublisher->documents()->isExists('bidon');
var_dump($isExists);