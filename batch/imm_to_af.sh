#!/bin/sh

echo Find all ImmExtjs files
find ./ -name "ImmExtjs*" >> temp.log
FILES=`cat temp.log`

for file in $FILES
do
  echo ${file/ImmExtjs/afExtjs}
  git mv $file ${file/ImmExtjs/afExtjs}
done