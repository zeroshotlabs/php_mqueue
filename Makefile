
MODULE_NAME = php_mqueue

FINAL_NAME = phphi_mqueue

LIBPHPHI_DIR = libphphi

include $(LIBPHPHI_DIR)/build.conf


.PHONY: all build shared build_lib static clean


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


$(LIB_DIR)/%.o: $(EXT_DIR)/%.c
	@mkdir -p $(LIB_DIR)
	$(CC) $(CFLAGS) -c $< -o $@


clean:
	$(MAKE) -C $(LIBPHPHI_DIR) clean
	rm -f $(LIB_DIR)/*.o $(LIB_DIR)/*.so $(LIB_DIR)/*.a

