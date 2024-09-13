<?php declare(strict_types=1);
namespace zeroshotlabs\php_mqueue;


// define('_HOME',__DIR__.'/');
// define('_PARENT',realpath(__DIR__.'/../'));


use \FFI;
use \FFI\CData as cdata;


require_once(__DIR__.'/libphphi/header.inc');

require(__DIR__.'/src/php_mqueue.php');




function _phphi_init(): bool
{
    // if( !empty($this->ffi) )
    //     throw new Exception("Invalid call");

    if( !is_readable($this->libphphi_so))
        throw new Exception("libphphi.so not readable from '".$this->libphphi_so."'");

    // if( !empty($this->load_so) )
    // {
    //     if( !is_readable($this->load_so) )
    //         throw new Exception("load_so not readable from '".$this->load_so."'");

    //     $this->cdef .= "\nvoid c2php_constants_other();";

    //     error_log("No other FFI loaded through libphphi");
    // }

//        $this->ffi = FFI::cdef($this->_libphphi_cdef,$this->libphphi_so);

    if( defined('_DEBUG') )
        $this->show_limits();

    return true;
}


