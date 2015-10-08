<?php
if(!defined('ROOT_PATH')){
    define('ROOT_PATH', __DIR__.'/..');
}
if(!defined('VENDOR_PATH')){
    define('VENDOR_PATH', ROOT_PATH.'/vendor');
}
if (file_exists($path = VENDOR_PATH.'/autoload.php')) {
    require $path;
}