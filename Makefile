
MODULE_NAME = php_mqueue

LIBPHPHI_DIR = libphphi


ifneq ("$(wildcard $(LIBPHPHI_DIR)/build/common.mk)","")
    include $(LIBPHPHI_DIR)/build/common.mk
endif



.PHONY: fetch_libs build build_module clean all


fetch_libs:
	echo -e $(OBJS)
	@echo -e "\n\n=== Updating submodules...\n"
	git submodule update --init --remote --recursive libphphi
	@echo -e "\n=== Submodules updated.\n\n"

build:
	@echo -e "\n\n=== Building_b libphphi..."
	$(MAKE) fetch_libs
	$(MAKE) -C $(LIBPHPHI_DIR) build_libphphi
	@echo -e "\n\n=== Building $(MODULE_NAME)..."
	$(MAKE) build_module

build_module:
	@echo -e "\n\n=== Building_bm_ $(MODULE_NAME)..."
	$(MAKE) shared
	$(MAKE) static

clean:
	$(MAKE) -C $(LIBPHPHI_DIR) clean_libphphi
	rm -f $(LIB_DIR)/*.o $(LIB_DIR)/*.so $(LIB_DIR)/*.a


all: build


