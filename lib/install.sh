#!/bin/bash
CURRENT_PATH=`pwd`
LOCAL_ROOT=`cd ~ && pwd`
PHP_PATH=$LOCAL_ROOT/local/php-5.2.4/lib/php

cd PHP_PATH && ln -s CURRENT_PATH library
