// #define FFI_SCOPE "MQUEUE_FFI"
// #define FFI_LIB "/root/working/modules/php_mqueue/lib/phphi_mqueue.so"
// #define FFI_LIB "librt.so.1"

#include <fcntl.h>
#include <mqueue.h>
#include <stdio.h>
#include <stdlib.h>
#include <sys/stat.h>
#include <unistd.h>


#include <mqueue.h>
#include "libphphi/ext/libphphi.h"


#ifndef MQUEUE_FFI_H
#define MQUEUE_FFI_H


#define _GNU_SOURCE

typedef int mqd_t;
typedef unsigned int mode_t;
typedef long int ssize_t;
typedef unsigned long size_t;


#define O_RDONLY    00
#define O_WRONLY    01
#define O_RDWR      02
#define O_CREAT   0100
#define O_EXCL    0200
#define O_NONBLOCK 04000



#endif



mqd_t mq_open(const char *name, int oflag, ...);
int mq_close(mqd_t mqdes);
int mq_unlink(const char *name);
int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);
ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);
int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);



// mqd_t mq_open(const char *name, int oflag, ...);


// typedef const struct mq_attr {
//     long mq_flags;       /* Flags: 0 or O_NONBLOCK */
//     long mq_maxmsg;      /* Max. # of messages */
//     long mq_msgsize;     /* Max. message size (bytes) */
//     long mq_curmsgs;     /* # of messages currently in queue */
// } mq_attr;





