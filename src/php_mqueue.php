<?php declare(strict_types=1);
namespace zeroshotlabs\php_mqueue;

use \Exception;
use \FFI;
use \FFI\CData as cdata;

use zeroshotlabs\libphphi\libphphi;

use function zeroshotlabs\libphphi\plog;




class php_mqueue
{
    use libphphi;

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
        if( $recv_buf_len > 0 )
            $this->recv_buf_len = $recv_buf_len;

        $this->this_pid = posix_getpid();

        $this->ffi = FFI::load(_MQ_HOME.'/ext/mqueue_ffi.h');

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

        return $this->name;
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

