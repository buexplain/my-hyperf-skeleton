#!/bin/bash
# 文档编译脚本，可将markdown文档编译为html文档
mkdir -p ./../public/doc
rm -rf ./../public/doc/*
chmod u+x ./../vendor/bin/mddoc
chmod u+x ./../vendor/buexplain/mddoc/bin/mddoc
./../vendor/bin/mddoc make ./ ../public/doc README.md public/doc