# php-project-lvl2

[![Maintainability](https://api.codeclimate.com/v1/badges/d21445c3c14983d3e7be/maintainability)](https://codeclimate.com/github/AlexeyShobanov/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/d21445c3c14983d3e7be/test_coverage)](https://codeclimate.com/github/AlexeyShobanov/php-project-lvl2/test_coverage)
[![PHP CI](https://github.com/AlexeyShobanov/php-project-lvl2/workflows/PHP%20CI/badge.svg)](https://github.com/AlexeyShobanov/php-project-lvl2/actions)

## Install

$ composer global require alshad/gendiff

https://asciinema.org/a/fgL24h2P4rSsYnsFRjxW3iIoN

## CLI for Generate diff

### Help and Version

$ gendiff -h

$ gendiff -v

https://asciinema.org/a/iNDdvVwz1nXgT4VeYgZ4q7CGn

## Compare 2 files

### Generate diff for flat .json files (files are in the working directory)

$gendiff file1.json file2.json.

https://asciinema.org/a/dNepFcHJ4ZPoD92Rd8ZSWTdzx

### Generate diff for flat .yaml files (files are in the working directory)

$gendiff file1.yaml file2.yaml

https://asciinema.org/a/1TgByMyPBKlBjIp1MfRMJCWNZ


### Generate diff for recursive .json files (files are in the working directory)

$gendiff file1.json file2.json

https://asciinema.org/a/6S60yo57AR5cAgHbKV9DSKa5y

### Generate diff for recursive .yaml files (files are in the working directory)

$gendiff file1.yaml file2.yaml

https://asciinema.org/a/uUwbynJfhzrQqvFCqCnKlaCrr

### Generate diff for recursive .json files (files are in the working directory) and output in format "plain"

$gendiff --format plain file1.json file2.json

https://asciinema.org/a/EIcWFiEiNwMyANoBjFbcKBXu7

### Generate diff for recursive .json files (files are in the working directory) and output in format "json"

$gendiff --format json file1.json file2.json

https://asciinema.org/a/V0aWiNg63G6EW1sszyrR9RxIK