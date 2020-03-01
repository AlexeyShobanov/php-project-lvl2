# php-project-lvl2

[![Build Status](https://travis-ci.org/AlexeyShobanov/php-project-lvl2.svg?branch=master)](https://travis-ci.org/AlexeyShobanov/php-project-lvl2)
[![Maintainability](https://api.codeclimate.com/v1/badges/d21445c3c14983d3e7be/maintainability)](https://codeclimate.com/github/AlexeyShobanov/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/d21445c3c14983d3e7be/test_coverage)](https://codeclimate.com/github/AlexeyShobanov/php-project-lvl2/test_coverage)

## Install

$ composer global require alshad/gendiff

https://asciinema.org/a/9N560K0rdyDShhCh9I8aOP1ty

## CLI for Generate diff

### Help and Version

$ gendiff -h

$ gendiff -v

https://asciinema.org/a/3h4A3SwHfa3gkwTXz6aEuFECz

## Compare 2 files

### Generate diff for flat .json files (files are in the working directory)

$gendiff file1.json file2.json.

https://asciinema.org/a/s2c69tbSLrGiWHI504UaLx89C

### Generate diff for flat .yaml files (files are in the working directory)

$gendiff file1.yaml file2.yaml

https://asciinema.org/a/l2ivTFsa2PKke6sFa5qjr4LqW


### Generate diff for recursive .json files (files are in the working directory)

$gendiff file1.json file2.json

https://asciinema.org/a/GQBqsKSybYeplw8HwX3LdXkC3

### Generate diff for recursive .yaml files (files are in the working directory)

$gendiff file1.yaml file2.yaml

https://asciinema.org/a/hgeNt6HAMacIrTadwQ5WLKCPY

### Generate diff for recursive .json files (files are in the working directory) and output in format "plain"

$gendiff --format plain file1.json file2.json



### Generate diff for recursive .json files (files are in the working directory) and output in format "json"

$gendiff --format json file1.json file2.json

