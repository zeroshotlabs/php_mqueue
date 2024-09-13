#define _GNU_SOURCE
#include "mqueue_ffi.h"


mqd_t mq_open(const char *name, int oflag,...);

int mq_close(mqd_t mqdes);

int mq_unlink(const char *name);

int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);
ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);


int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);









// #include <mqueue.h>
// #include <sys/resource.h>




// #define FFI_LIB "libc.so.6"
// #define FFI_LIB "./mylib.so"
 // #define FFI_SCOPE "MYLIB"


// typedef __rlimit_resource_t __rlimit_resource_t;


// typedef unsigned long rlim_t;

// typedef  struct rlimit rlimit;

// int setrlimit(__rlimit_resource_t resource, const struct rlimit *rlim);

// int getrlimit(__rlimit_resource_t resource, struct rlimit *rlim);










// int mq_notify(mqd_t mqdes, const struct sigevent *notification);



// struct mq_attr {
//     long mq_flags;
//     long mq_maxmsg;
//     long mq_msgsize;
//     long mq_curmsgs;
// } mq_attr;



// mqd_t mq_open(const char *name, int oflag, mode_t mode, struct mq_attr *attr);


// Custom handling of strerror for PHP FFI if needed
// extern int errno;
// char *strerror(int errno);
