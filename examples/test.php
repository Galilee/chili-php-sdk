<?php
require 'initAutoloader.php';

use \Galilee\PPM\SDK\Chili;

$yaml = file_get_contents('config' . DIRECTORY_SEPARATOR . 'simple.yml');

$config = Chili\Config\ConfigService::fromYaml($yaml);

$cp = new Chili\ChiliPublisher($config);

/** @var Galilee\PPM\SDK\Chili\Service\Document $document */
$document = $cp->getDocument();

var_dump(get_class($document));

$document->load('067cd7e9-ed66-40ca-b326-ef2312ddac11'); // Demo\Cartes\GalileeV2.xml
//$document->load('9e3448c7-7d39-415d-8116-1929850769d8'); // copy-test

var_dump($document->getName());
var_dump($document->getId());
var_dump($document->getPath());


$variableValues = $document->getVariableValues();
var_dump($variableValues);
/*
$domVariables = $variableValues->asDomXml();
$variables = $domVariables->getElementsByTagName('item');
foreach($variables as $var) {
    if ($var->hasAttribute("name") && $var->getAttribute("name") == 'Nom') {
        $var->setAttribute("value", "toto");
    }
}

$new = new Galilee\PPM\SDK\Chili\Client\ResultXml($domVariables->saveXML());
var_dump($new->asString());

var_dump($document->setVariableValues($new));
*/

// Preview
$url = $document->getUrl(Chili\ChiliPublisher::PREVIEW_TYPE_FULL, 1);
echo '<img src="' . $url . '" />';

// Copy
//var_dump($document->copy('copy-test', 'Demo\Cartes'));


// Appeler une méthode non implémentée par le sdk :
// Les paramètres sont identiques aux méthodes natives Chili sans l'api key.
$result = $cp->soapCall->resourceItemGetDefinitionXML(
    array(
        'resourceName' => $cp::RESOURCE_NAME_PDF_EXPORT_SETTINGS,
        'itemID' => 'b5aca7ee-4af7-4ca2-ac76-d1cc7bbea640'
    )
);

var_dump($result);

