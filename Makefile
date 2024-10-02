

MODULE_NAME = php_mqueue

CC = gcc
AR = ar

EXT_DIR = ext
LIB_DIR = lib

## CFLAGS = -Wall -Wextra -I./libphphi/include
CFLAGS = -Wall -Wextra -fPIC -I./include

## LDFLAGS = -L./libphphi
LDFLAGS = -L./libphphi -ldl -lrt -lphphi

SRCS = $(wildcard $(EXT_DIR)/*.c)
OBJS = $(SRCS:.c=.o)

.PHONY: all clean static shared


all: static shared

static: $(MODULE_NAME).a

shared: $(MODULE_NAME).so

$(MODULE_NAME).a: $(OBJS) libphphi/libphphi.a
	$(CC) -static -o $@ $(OBJS) $(LDFLAGS)

$(MODULE_NAME).so: $(OBJS) libphphi/libphphi.so
	$(CC) -shared -o $@ $(OBJS) $(LDFLAGS)

libphphi/libphphi.a libphphi/libphphi.so:
	$(MAKE) -C libphphi

clean:
	rm -f *.o $(MODULE_NAME)_static.so $(MODULE_NAME).so
	$(MAKE) -C libphphi clean

# .gitmodules






# MODULE_NAME = php_mqueue


# LIB_PATH = $(LIB_DIR)/phphi_mqueue.so

# .PHONY: all build shared build_lib static clean


# all: shared


# test: all
# 	php test.php


# build: $(OBJS)
# 	@echo -e "\n\nMQ USING PHP INC: $(PHP_INCLUDE_DIRS)"
# 	@echo -e "\n CFLAGS: $(CFLAGS)"
# 	@echo -e "\nLDFLAGS: $(LDFLAGS)"
# 	@echo -e "\n   OBJS: $(OBJS)"
# 	@echo -e "\n   LINK: $(OBJS)"
# 	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$(FINAL_NAME).so $(LIBPHPHI_DIR)/lib/libphphi.o $(OBJS) -ldl -lrt


# shared: build_lib $(MODULE_NAME).so build


# build_lib: ${LIBPHPHI_DIR}/
# 	@echo -e "\n\n=== Pull/build libphphi..."
# 	git submodule update --init --remote --recursive
# 	$(MAKE) -C $(LIBPHPHI_DIR) shared


# $(MODULE_NAME).so: $(OBJS) $(LIBPHPHI_DIR)/lib/libphphi.o
# 	@echo -e "\n\n=== Building $(MODULE_NAME).so..."
# 	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$@ $(OBJS) -ldl -lrt


# $(LIB_DIR)/%.o: $(EXT_DIR)/%.c
# 	@mkdir -p $(LIB_DIR)
# 	$(CC) $(CFLAGS) -c $< -o $@


# clean:
# ifneq ($(LIBPHPHI_DIR),)
# 	$(MAKE) -C $(LIBPHPHI_DIR) clean
# endif
# 	rm -f $(LIB_DIR)/*.o $(LIB_DIR)/*.so $(LIB_DIR)/*.aa

