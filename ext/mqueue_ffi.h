#define _GNU_SOURCE
// #ifndef MQUEUE_FFI_H
// #define MQUEUE_FFI_H

#include <stdint.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/resource.h>
#include <fcntl.h>
#include <sys/stat.h>
#include <mqueue.h>



extern int errno;
char *strerror(int errno);

typedef struct mq_attr mq_attr;

typedef int mqd_t;
typedef unsigned int mode_t;




// typedef struct mq_attr {
//     long mq_flags;
//     long mq_maxmsg;
//     long mq_msgsize;
//     long mq_curmsgs;
// } mq_attr;



// #include <errno.h>
// #include <fcntl.h>
// #include <stdint.h>
// #include <sys/stat.h>
// #include <sys/types.h>
// #include <sys/resource.h>

// #include <mqueue.h>


// extern int errno;
// char *strerror(int errno);







// mqd_t mq_open(const char *name, int oflag, mode_t mode, struct mq_attr *attr);


// int mq_close(mqd_t mqdes);

// int mq_unlink(const char *name);

// int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);
// ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);

// int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
// int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);

// int mq_notify(mqd_t mqdes, const struct sigevent *notification);



// #endif // MQUEUE_FFI_H


