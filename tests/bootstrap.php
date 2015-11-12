<?php

define('ROOT_PATH', __DIR__.'/..');
define('VENDOR_PATH', ROOT_PATH.'/vendor');

if (file_exists($path = VENDOR_PATH.'/autoload.php')) {
    require $path;
}

error_reporting(E_ALL);
