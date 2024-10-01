// // (C) 2024 Zero Shot Labs, Inc. See LICENSE for details.


// #include <fcntl.h>
// #include <sys/stat.h>
// #include <sys/types.h>

// #include "mqueue_ffi.h"

// #include <fcntl.h>
// #include <sys/stat.h>
// #include <sys/types.h>
// #include <stdlib.h>
// #include <errno.h>
// #include <mqueue.h>

#include "mqueue_ffi.h"



typedef mqd_t mqd_t;
typedef mode_t mode_t;
typedef ssize_t ssize_t;
typedef size_t size_t;
typedef struct mq_attr_t mq_attr_t;



// extern mqd_t mq_open( const char *name, int oflag, mode_t mode, struct mq_attr *attr);


// int mq_close(mqd_t mqdes);
// ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);
// int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);
// int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
// int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);


// typedef int mqd_t;
// typedef unsigned int mode_t;

// // typedef struct mq_attr {
// //     long mq_flags;       /* Flags: 0 or O_NONBLOCK */
// //     long mq_maxmsg;      /* Max. # of messages on queue */
// //     long mq_msgsize;     /* Max. message size (bytes) */
// //     long mq_curmsgs;     /* # of messages currently in queue */
// // } mq_attr_t;
 
// mqd_t mq_open( const char *name, int oflag, mode_t mode, struct mq_attr *attr);


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





// // #define _GNU_SOURCE


// // #include <fcntl.h>
// // #include <mqueue.h>
// // #include <stdio.h>
// // #include <stdlib.h>
// // #include <sys/stat.h>
// // #include <unistd.h>

// // #include <mqueue.h>
// // #include <sys/resource.h>

// // #include "ext/mqueue_ffi.h"


// // typedef const struct mq_attr mq_attr;

// // typedef int mqd_t;

// // // mqd_t mq_open(const char *name, int oflag,...);
// // // int mq_close(mqd_t mqdes);
// // // int mq_unlink(const char *name);
// // // int mq_getattr(mqd_t mqdes, struct mq_attr *attr);
// // // int mq_setattr(mqd_t mqdes, const struct mq_attr *newattr, struct mq_attr *oldattr);
// // // ssize_t mq_receive(mqd_t mqdes, char *msg_ptr, size_t msg_len, unsigned int *msg_prio);
// // // int mq_send(mqd_t mqdes, const char *msg_ptr, size_t msg_len, unsigned int msg_prio);






// // typedef __rlimit_resource_t __rlimit_resource_t;


// // typedef unsigned long rlim_t;

// // typedef  struct rlimit rlimit;

// // int setrlimit(__rlimit_resource_t resource, const struct rlimit *rlim);

// // int getrlimit(__rlimit_resource_t resource, struct rlimit *rlim);




// // int mq_notify(mqd_t mqdes, const struct sigevent *notification);





// // // mqd_t mq_open(const char *name, int oflag, mode_t mode, struct mq_attr *attr);


// // // Custom handling of strerror for PHP FFI if needed
// // // extern int errno;
// // // char *strerror(int errno);

// #include "mqueue_ffi.h"
// #include <fcntl.h>
// #include <stdio.h>
// #include <stdlib.h>
// #include <unistd.h>
// #include <mqueue.h>

// // Function implementations
