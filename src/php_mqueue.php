<?php declare(strict_types=1);
namespace zsl\php_mqueue;

use \Exception;
use \FFI;
use \FFI\CData as cdata;

use zsl\libphphi\c2consts;
use zsl\libphphi\t_libphphi as tlib;

use function zsl\libphphi\plog;



/**
 * @todo what is going on with $quiet
 */



// function _phphi_init(): bool
// {
//     // if( !empty($this->ffi) )
//     //     throw new Exception("Invalid call");

//     // if( !is_readable($this->libphphi_so))
//     //     throw new Exception("libphphi.so not readable from '".$this->libphphi_so."'");

//     // if( !empty($this->load_so) )
//     // {
//     //     if( !is_readable($this->load_so) )
//     //         throw new Exception("load_so not readable from '".$this->load_so."'");

//     //     $this->cdef .= "\nvoid c2php_constants_other();";

//     //     error_log("No other FFI loaded through libphphi");
//     // }

// //        $this->ffi = FFI::cdef($this->_libphphi_cdef,$this->libphphi_so);

//     if( defined('_DEBUG') )
//         $this->show_limits();

//     return true;
// }



class php_mqueue extends c2consts
{
    use tlib;


    public bool $verbose = false;
    public int $mode = 0666;
    public int $flags = 0;
    public string $name = '';

    public $mqdes;

    public int $recv_buf_len = 4096*16;

    public int $recvd_len = 0;
    public cdata $recv_buf;
    public cdata $recv_buf_addr;

    public cdata $priority;
    public cdata $priority_addr;

    public array $attr = [];


    public function __construct( int $recv_buf_len = 0 )
    {
//        $this->_phphi_init();

        if( $recv_buf_len > 0 )
            $this->recv_buf_len = $recv_buf_len;

        $this->this_pid = posix_getpid();

        // $this->ffi = FFI::cdef(file_get_contents(_MQ_HOME.'/ext/mqueue_ffi.c'),
        //                         '/root/working/modules/php_mqueue/lib/php_mqueue.so');
        
        $this->ffi = FFI::cdef(file_get_contents(_MQ_HOME.'/ext/mqueue_ffi.h'),
                                '/root/working/modules/php_mqueue/lib/php_mqueue.so');

        // $this->ffi = FFI::cdef(file_get_contents(_MQ_HOME.'/ext/mqueue_ffi.c'),
        //                         '/root/working/modules/php_mqueue/lib/php_mqueue.so');


// $attr = $this->ffi->new("struct mq_attr");


        $this->set_consts();

        $this->recv_buf = $this->ffi->new("char[{$this->recv_buf_len}]");
        $this->recv_buf_addr = FFI::addr($this->recv_buf);
        
        $this->priority = $this->ffi->new("unsigned int");
        $this->priority_addr = FFI::addr($this->priority);

        $this->zerod($this->recv_buf);
        $this->zerod($this->priority);
    }

    public function __toString()
    {
        return $this->name;
    }

    // read-only won't be O_CREATE'd
    public function open( string $name,int $msg_size,int $msg_count,int $rdwr,bool $blocking ): string
    {
        $this->name = '/'.str_replace('\/ ', '_', trim($name, '\/ '));

        if( $rdwr === self::O_RDONLY )
            $this->flags = self::O_RDONLY;
        elseif( $rdwr === self::O_WRONLY )
            $this->flags = self::O_WRONLY | self::O_CREAT;
        else
            $this->flags = self::O_RDWR | self::O_CREAT;

        $this->flip_blocking($blocking);

        $mq_attr = $this->getattr();

        $mq_attr->mq_maxmsg = $msg_count;
        $mq_attr->mq_msgsize = $msg_size;
        $mq_attr->mq_flags = $this->flags;

        $old_umask = umask(0);
        $this->mqdes = $this->ffi->mq_open($this->name,$this->flags,$this->mode,FFI::addr($mq_attr));
        umask($old_umask);

        if ($this->mqdes === -1)
            throw new Exception(posix_getpid().": mq_open failed for {$this->name} {$mq_attr->mq_maxmsg} / {$mq_attr->mq_msgsize}: ".$this->strerror());

        $mq_attr->mq_maxmsg = $msg_count;
        $mq_attr->mq_msgsize = $msg_size;
        $mq_attr->mq_flags = $this->flags;

        if( $this->setattr($mq_attr) )
            return $this->name;
        else
            throw new Exception("Failed to open and set: ".$this->strerror());
    }

// 'mq_flags' => $mq_attr->mq_flags,
// 'mq_maxmsg' => $mq_attr->mq_maxmsg,
// 'mq_msgsize' => $mq_attr->mq_msgsize,
// 'mq_curmsgs' => $mq_attr->mq_curmsgs
    public function getattr( $blank = false ): cdata
    {
        $attr = $this->ffi->new("struct mq_attr");

        if( !$this->mqdes || $blank )
        {
            $this->zerod($attr);
            return $attr;
        }

        $result = $this->ffi->mq_getattr($this->mqdes,FFI::addr($attr));

        if ($result === -1)
            throw new Exception("Failed to get attributes: ".$this->strerror());

        return $attr;
    }

    public function setattr( cdata $attr ): bool
    {
        if( empty($this->mqdes) )
            throw new Exception("Queue is not open");

        $old = $this->ffi->new("struct mq_attr");
        $oldd = $this->ffi->new("struct mq_attr");

        $result = $this->ffi->mq_setattr($this->mqdes,FFI::addr($oldd),FFI::addr($old));

        if ($result === -1)
            throw new Exception("Failed to set attributes: ".$this->strerror());

        return true;
    }

    public function flip_verbose( bool $on ): bool
    {
        $this->verbose = !$this->verbose;
        return $this->verbose;
    }

    public function flip_blocking( bool $on ): bool
    {
        if( $on )
            return ((bool)($this->flags &= ~self::O_NONBLOCK));
        else
            return ((bool)($this->flags |= self::O_NONBLOCK));
    }

    public function send( string $message,int $priority = 0 ): int
    {
        static $send_len = 0;

        $send_len = $this->ffi->mq_send($this->mqdes,$message,strlen($message),$priority);

        if( $send_len !== -1 )
            return $send_len;

        $attr = $this->getattr();
        throw new Exception("mq_send failed: ".print_r($attr,true)."\n\n".$this->strerror()." | msg_len: ".strlen($message)
                            .'  '.substr(print_r($message,true),0,256).'....');
    }

    public function receive(): ?string
    {
        $this->zerod($this->recv_buf);

        $this->recvd_len = $this->ffi->mq_receive($this->mqdes,$this->recv_buf,$this->recv_buf_len,$this->priority_addr);

        if( $this->recvd_len !== -1 )
            return FFI::string($this->recv_buf,$this->recvd_len);

        $attr = $this->getattr();
        throw new Exception("mq_receive failed: ".print_r($attr,true)."\n\n".$this->strerror()." | recv_buf_len: ".$this->recv_buf_len
                            .'  '.substr(print_r($message,true),0,256).'....');
    }

    public function close( $quiet = true ): bool
    {
        if( !is_resource($this->mqdes) )
            if( !$quiet )
                throw new Exception("mq_close failed - unavailable ($this->mqdes): " . $this->strerror());
            else
                return false;

        $r = $this->ffi->mq_close($this->mqdes);

        if( $r < 0 )
            return ($quiet ?? !$this->verbose) ? throw new Exception("mq_close failed for {$this->name}: " . $this->strerror()) : false;
        else
            return true;
    }

    public function unlink( $quiet = true ): bool
    {
        if( !is_resource($this->mqdes) )
            if( !$quiet )
                throw new Exception("mq_close failed - unavailable ($this->mqdes): " . $this->strerror());
            else
                return false;

        if( !is_readable($this->name) )
            return true;

        $r = $this->ffi->mq_unlink($this->name);

        if( $r < 0 )
            return ($quiet ?? !$this->verbose) ? throw new Exception("mq_unlink failed for {$this->name}: " . $this->strerror()) : false;
        else
            return true;
    }

    public function shutdown( $quiet = true )
    {
        if( !$quiet )
            echo "\n\nbye.\n\n";

        $this->__destruct();
    }

    public function __destruct()
    {
        $this->unlink();
        $this->close();
    }
}





// $cdef = <<< __EOD__
// #include <dlfcn.h>
// #include <errno.h>
// #include <fcntl.h>
// #include <stdint.h>
// #include <string.h>
// #include <stdlib.h>
// #include <sys/resource.h>
// #include <sys/types.h>
// #include <sys/stat.h>
// #include "mqueue.h"

// typedef unsigned long rlim_t;

// struct rlimit {
//     rlim_t rlim_cur;
//     rlim_t rlim_max;
// };

// extern int errno;
// char *strerror(int errnum);

// int setrlimit(int resource, const struct rlimit *rlim);
// int getrlimit(int resource, struct rlimit *rlim);



// /* void c2php(void); */

// void c2php_const(const char *name, long value)
// {
//     zend_register_long_constant(name, strlen(name), value, CONST_CS | CONST_PERSISTENT, 0);
// }


// void c2php_common_consts()
// {
//     c2php_const("RLIM_INFINITY", RLIM_INFINITY);
//     c2php_const("RLIMIT_MSGQUEUE", RLIMIT_MSGQUEUE);
//     c2php_const("RLIMIT_NOFILE", RLIMIT_NOFILE);
//     c2php_const("O_RDONLY", O_RDONLY);
//     c2php_const("O_WRONLY", O_WRONLY);
//     c2php_const("O_RDWR", O_RDWR);
//     c2php_const("O_CREAT", O_CREAT);
//     c2php_const("O_EXCL", O_EXCL);
//     c2php_const("O_NONBLOCK", O_NONBLOCK);
// }



// typedef int mqd_t;
// typedef unsigned int mode_t;
// // typedef struct mq_attr_ mq_attr;

// typedef struct mq_attr {
//     long mq_flags;       /* Flags: 0 or O_NONBLOCK */
//     long mq_maxmsg;      /* Max. # of messages on queue */
//     long mq_msgsize;     /* Max. message size (bytes) */
//     long mq_curmsgs;     /* # of messages currently in queue */
// } mq_attr_t;
 
//         mqd_t mq_open( const char *name, int oflag, mode_t mode, struct mq_attr *attr);

// //        mqd_t mq_open(const char *name, int oflag, ...);
//         int mq_close(mqd_t mqdes);
//         int mq_unlink(const char *name);
//         int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
//         int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);
//         ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);
//         int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);
        

//             // typedef unsigned int key_t;
//             // typedef int shmatt_t;
//             // extern int errno;
//             // void *shmat(int shmid, const void *shmaddr, int shmflg);
//             // int shmdt(const void *shmaddr);
//             // int shmget(key_t key, size_t size, int shmflg);
//             // void *memcpy(void *dest, const void *src, size_t n);

// __EOD__;
