<?php declare(strict_types=1);
namespace zeroshotlabs\php_mqueue;



define('_MQ_HOME',__DIR__.'/');
define('_MQ_PARENT',realpath(__DIR__.'/../'));


use \FFI;
use \FFI\CData as cdata;


require(_MQ_HOME.'/libphphi/header.inc');

require(_MQ_HOME.'/src/php_mqueue.php');


