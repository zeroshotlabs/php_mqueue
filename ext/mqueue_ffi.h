// // (C) 2024 Zero Shot Labs, Inc. See LICENSE for details.


#ifndef MQUEUE_FFI_H
#define MQUEUE_FFI_H


#define _GNU_SOURCE



#define O_RDONLY    00
#define O_WRONLY    01
#define O_RDWR      02
#define O_CREAT   0100
#define O_EXCL    0200
#define O_NONBLOCK 04000


#include <fcntl.h>
#include <stdio.h>
#include <stdlib.h>
#include <sys/mman.h>
#include <sys/types.h>
#include <mqueue.h>

#include "libphphi/ext/libphphi.h"




extern int errno;
char *strerror(int errnum);

// #include <fcntl.h>
// #include <sys/stat.h>
// #include <sys/types.h>
// #include <mqueue.h>

// extern mqd_t mq_open(const char *, int, ...);


extern mqd_t mq_open( const char *name, int oflag, mode_t mode, struct mq_attr *attr);


int mq_close(mqd_t mqdes);
ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);
int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);
int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);

// mqd_t mq_open(const char *name, int oflag, ...);

// // Ensure mq_attr is fully defined
// typedef struct mq_attr {
//     long mq_flags;       /* Flags: 0 or O_NONBLOCK */
//     long mq_maxmsg;      /* Max. # of messages on queue */
//     long mq_msgsize;     /* Max. message size (bytes) */
//     long mq_curmsgs;     /* # of messages currently in queue */
// } mq_attr_t;

#endif


