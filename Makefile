
MODULE_NAME = php_mqueue

FINAL_NAME = phphi_mqueue

LIBPHPHI_DIR = libphphi

include $(LIBPHPHI_DIR)/build.conf




# gcc -fPIC -c libphphi.c -o libphphi.o
# gcc -fPIC -c mqueue_ffi.c -o mqueue_ffi.o

# gcc -shared -fPIC -o libphp_mqueue.so libphphi.c mqueue_ffi.c -lrt
# gcc -shared -fPIC -o lib/libphphi.so lib/libphphi.o -ldl -lrt
# gcc -shared -fPIC -o libphp_mqueue.so libphphi.o mqueue_ffi.o -ldl -lrt


.PHONY: all fetch_libphphi shared static clean

all: shared

build: $(OBJS)
	@echo -e "\n\nMQ USING PHP INC: $(PHP_INCLUDE_DIRS)"
	@echo -e "\n CFLAGS: $(CFLAGS)"
	@echo -e "\nLDFLAGS: $(LDFLAGS)"
	@echo -e "\n   OBJS: $(OBJS)"
	@echo -e "\n   LINK: $(OBJS)"
	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$(FINAL_NAME).so $(LIBPHPHI_DIR)/lib/libphphi.o $(OBJS) -ldl -lrt

shared: build_lib $(MODULE_NAME).so build

build_lib: ${LIBPHPHI_DIR}/${EXT_DIR}/*
	@echo -e "\n\n=== Pull/build libphphi..."
	git submodule update --init --remote --recursive $(LIBPHPHI_DIR)
	$(MAKE) -C $(LIBPHPHI_DIR) shared


$(MODULE_NAME).so: $(OBJS) $(LIBPHPHI_DIR)/lib/libphphi.o
	@echo -e "\n\n=== Building $(MODULE_NAME)..."
	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$@ $(OBJS) -ldl -lrt

# $(MODULE_NAME).so: $(OBJS)
# 	@echo -e "\n\n=== Building $(MODULE_NAME)..."
# 	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$@ $^

$(LIB_DIR)/%.o: $(EXT_DIR)/%.c
	@mkdir -p $(LIB_DIR)
	$(CC) $(CFLAGS) -c $< -o $@

clean:
	$(MAKE) -C $(LIBPHPHI_DIR) clean
	rm -f $(LIB_DIR)/*.o $(LIB_DIR)/*.so $(LIB_DIR)/*.a


# CC = gcc
# AR = ar
# CFLAGS = -Wall -Wextra -O2 -fPIC $(PHP_INCLUDE_DIRS) -I./$(EXT_DIR)
# LDFLAGS = -shared -ldl -lc -lrt

# EXT_DIR = ext
# LIB_DIR = lib
# SRCS = $(wildcard $(EXT_DIR)/*.c)
# OBJS = $(patsubst $(EXT_DIR)/%.c,$(LIB_DIR)/%.o,$(SRCS))

# # .PHONY: all clean build build_lib

# # CHECK_PHP_CONFIG = $(shell which $(PHP_CONFIG) > /dev/null 2>&1 || echo "Error")

# # ifeq ($(CHECK_PHP_CONFIG), Error)
# # 	$(error "php-config not found - Try PHP_CONFIG=/usr/local/path/php/bin/php-config make")
# # endif

# # # libphphi-init:
# # # 	@git submodule init
# # # 	@git submodule update --remote
# # # 	@echo "Submodules initialized and updated."

# # build_lib: $(OBJS)
# # 	@git submodule init
# # 	@git submodule update --remote
# # 	@echo "Submodules updated."
# # 	@mkdir -p $(LIB_DIR)
# # 	$(MAKE) shared
# # 	$(MAKE) static
# # 	@echo "CFLAGS: $(CFLAGS)"

# # static: $(MODULE_NAME).a

# # shared: $(MODULE_NAME).so

# # clean:
# # 	rm -f $(LIB_DIR)/*.o $(LIB_DIR)/*.so $(LIB_DIR)/*.a

# # $(MODULE_NAME).so: $(OBJS)
# # 	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$@ $^

# # $(MODULE_NAME).a: $(OBJS)
# # 	$(AR) rcs $(LIB_DIR)/$@ $^

# # $(LIB_DIR)/%.o: $(EXT_DIR)/%.c
# # 	@mkdir -p $(LIB_DIR)
# # 	$(CC) $(CFLAGS) -c $< -o $@




# .PHONY: all build_libphphi


# # # Check if the submodule is initialized
# # check-submodule:
# # 	@if [ ! -d "path/to/submodule/.git" ]; then \
# # 		echo "Submodule not initialized, initializing now..."; \
# # 		git submodule init; \
# # 	fi

# # update_subs:
# # 	@git submodule update --init --remote --recursive
# # 	@echo "Submodules updated."


# build_libphphi: $(OBJS)
# 	@mkdir -p $(LIB_DIR)
# 	$(MAKE) shared
# 	$(MAKE) static
# 	@echo "CFLAGS: $(CFLAGS)"

# shared: $(MODULE_NAME).so

# static: $(MODULE_NAME).a

# clean:
# 	rm -f $(LIB_DIR)/*.o $(LIB_DIR)/*.so $(LIB_DIR)/*.a

# $(MODULE_NAME).so: $(OBJS)
# 	$(CC) $(LDFLAGS) -o $(LIB_DIR)/$@ $^

# $(MODULE_NAME).a: $(OBJS)
# 	$(AR) rcs $(LIB_DIR)/$@ $^

# $(LIB_DIR)/%.o: $(EXT_DIR)/%.c
# 	@mkdir -p $(LIB_DIR)
# 	$(CC) $(CFLAGS) -c $< -o $@



# # Check if the submodule is initialized
# check-submodule:
# 	@if [ ! -d "path/to/submodule/.git" ]; then \
# 		echo "Submodule not initialized, initializing now..."; \
# 		git submodule init; \
# 	fi

# update_subs:
# 	@git submodule update --init --remote --recursive
# 	@echo "Submodules updated."


