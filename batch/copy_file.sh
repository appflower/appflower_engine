#!/bin/sh

SOURCE_FILE=$1
TARGET_FILE=$2

if [ -e $SOURCE_FILE ]; then
	cp $SOURCE_FILE $TARGET_FILE
	rm -rf $SOURCE_FILE
fi