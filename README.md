# php_mqueue
Speed-optimized PHP FFI bindings for POSIX message queues

This is a PHP FFI binding for the POSIX C mq* functions.  All methods are
implemented except mq_notify (PRs welcome).

It should build with:
```
 - $ composer require zeroshotlabs/php_mqueue

 - $ PHP_CONFIG=/path/to/bin/php-config \
     make -C vendor/zeroshotlabs/php_mqueue clean

 - $ PHP_CONFIG=/path/to/bin/php-config \
     make -C vendor/zeroshotlabs/php_mqueue all
```

If composer won't install it, you can try directly from git:
```
    $ git clone https://github.com/zeroshotlabs/php_mqueue && cd php_mqueue
    $ PHP_CONFIG=/path/to/bin/php-config make all

In PHP, use the FFI::load() and FFI::scope() methods to load and access
specified functions, classes, etc.

PRs and tickets welcome (especially PRs).

