<?php
require('header.inc');

use zeroshotlabs\php_mqueue\php_mqueue as pmq;

$ffi = new pmq(1024);

if( $name = $ffi->open('/tiptop',32,4,pmq::O_RDWR,FALSE) ) {
    echo "\n\n=== open ok with '{$name}\n";
} else {
    throw new Exception("open failed");
}

$buffer = FFI::new("char[256]");
$prio = FFI::new("unsigned int");

$queue_name = "/test_queue";
$message = "Hello, World!";



$i = 10;
while( --$i ) {
    $ffi->send($message."  -  $i");
    usleep(100000);
    echo "\n\nRECV: ".$ffi->receive();
}


$ffi->shutdown(false);


