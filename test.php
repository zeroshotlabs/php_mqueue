<?php declare(strict_types=1);


FFI::load('ext/mqueue_ffi.h');

$mq = FFI::scope("MQUEUE_FFI");

$attr = $mq->new("mq_attr");

var_dump($attr);


// FFI::load('mqueue_ffi.h');  // Header with full path to the .so
// $mq = FFI::scope("MQUEUE_FFI");


// FFI::load('ext/mqueue_ffi.h');


// $mq = FFI::scope("MQUEUE_FFI");

// $attr = $mq->new("mq_attr");

// // You can still access the struct members as before
// $attr->mq_flags = 0;
// $attr->mq_maxmsg = 10;
// $attr->mq_msgsize = 8192;
// $attr->mq_curmsgs = 0;




// require('header.inc');

// use zeroshotlabs\php_mqueue\php_mqueue as pmq;

// $pmq_name = '/test_queue';
// $max_size = 1024;
// $max_msg_count = 10;

// // public function open( string $name,int $msg_size,int $msg_count,int $rdwr,bool $blocking ): string


// $mq_parent = new pmq;
// $mq_parent->open($pmq_name,$max_size,$max_msg_count,pmq::O_RDWR,true);


// // Create or open the message queue
// $queue = msg_get_queue($queueName, 0644);
// if ($queue === false)
//     exit("Failed to create/open message queue\n");

// echo "\n\nMessage queue created/opened successfully in PID: ".posix_getpid();

// // Send a message
// $message = "Hello, POSIX message queue!";
// if (msg_send($queue, 1, $message, true, false, $errorcode) === false) {
//     echo "Failed to send message. Error code: $errorcode\n";
// } else {
//     echo "Message sent successfully\n";
// }

// // Receive a message
// $msgtype = 0;
// $maxsize = 1024;
// if (msg_receive($queue, 0, $msgtype, $maxsize, $message, true, MSG_IPC_NOWAIT, $errorcode) === false) {
//     echo "Failed to receive message. Error code: $errorcode\n";
// } else {
//     echo "Received message: $message\n";
// }




























// // Get queue status
// $queue_status = msg_stat_queue($queue);
// if ($queue_status !== false) {
//     echo "Queue status:\n";
//     print_r($queue_status);
// } else {
//     echo "Failed to get queue status\n";
// }

// // Remove the queue
// if (msg_remove_queue($queue) === false) {
//     echo "Failed to remove message queue\n";
// } else {
//     echo "Message queue removed successfully\n";
// }



// Load the FFI module
// $ffi = FFI::cdef("
//     typedef struct {
//         long mq_flags;
//         long mq_maxmsg;
//         long mq_msgsize;
//         long mq_curmsgs;
//     } mq_attr;

//     int mq_open(const char *name, int oflag, ...);
//     int mq_close(int mqdes);
//     int mq_unlink(const char *name);
//     int mq_send(int mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);
//     ssize_t mq_receive(int mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);
//     int mq_getattr(int mqdes, mq_attr *attr);
// ", "lib/libposix_mqueue.so");

////////////////////////////////////

// // Test mq_open
// $queueName = "/test_queue";
// $queueDescriptor = $ffi->mq_open($queueName, 0666 | 0x0001); // O_RDWR | O_CREAT
// if ($queueDescriptor == -1) {
//     die("Failed to open message queue\n");
// }
// echo "Message queue opened successfully\n";

// // Test mq_send
// $message = "Hello, POSIX message queue!";
// $result = $ffi->mq_send($queueDescriptor, $message, strlen($message), 1);
// if ($result == -1) {
//     echo "Failed to send message\n";
// } else {
//     echo "Message sent successfully\n";
// }

// // Test mq_receive
// $buffer = FFI::new("char[1024]");
// $priority = FFI::new("unsigned int");
// $bytesReceived = $ffi->mq_receive($queueDescriptor, $buffer, 1024, $priority);
// if ($bytesReceived == -1) {
//     echo "Failed to receive message\n";
// } else {
//     $receivedMessage = FFI::string($buffer, $bytesReceived);
//     echo "Received message: $receivedMessage\n";
// }

// // Test mq_getattr
// $attr = $ffi->new("mq_attr");
// $result = $ffi->mq_getattr($queueDescriptor, FFI::addr($attr));
// if ($result == -1) {
//     echo "Failed to get queue attributes\n";
// } else {
//     echo "Queue attributes:\n";
//     echo "  mq_flags: {$attr->mq_flags}\n";
//     echo "  mq_maxmsg: {$attr->mq_maxmsg}\n";
//     echo "  mq_msgsize: {$attr->mq_msgsize}\n";
//     echo "  mq_curmsgs: {$attr->mq_curmsgs}\n";
// }

// // Test mq_close
// $result = $ffi->mq_close($queueDescriptor);
// if ($result == -1) {
//     echo "Failed to close message queue\n";
// } else {
//     echo "Message queue closed successfully\n";
// }

// // Test mq_unlink
// $result = $ffi->mq_unlink($queueName);
// if ($result == -1) {
//     echo "Failed to unlink message queue\n";
// } else {
//     echo "Message queue unlinked successfully\n";
// }

// echo "Tests completed.\n";



