<?php declare(strict_types=1);
namespace zeroshotlabs\php_mqueue;


define('_HOME',__DIR__.'/');
define('_PARENT',realpath(__DIR__.'/../'));


use \FFI;
use \FFI\CData as cdata;


require_once(__DIR__.'/libphphi/header.inc');

require(__DIR__.'/src/php_mqueue.php');

