<?php
require 'initAutoloader.php';

$yaml = file_get_contents('data'.DIRECTORY_SEPARATOR.'simple.yml');
$xml = file_get_contents('data'.DIRECTORY_SEPARATOR.'simple.xml');

$test = new \Galilee\PPM\SDK\Chili\Config\ConfigService('yaml', $yaml);
$test = new \Galilee\PPM\SDK\Chili\Config\ConfigService('xml', $xml);