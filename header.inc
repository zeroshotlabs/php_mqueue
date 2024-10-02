<?php declare(strict_types=1);
namespace zsl\php_mqueue;


use \FFI;
use \FFI\CData as cdata;



define('_MQ_HOME',__DIR__.'/');
define('_MQ_PARENT',realpath(__DIR__.'/../'));



require(_MQ_HOME.'/libphphi/header.inc');

require(_MQ_HOME.'/src/php_mqueue.php');


