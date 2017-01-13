# Composer json and lock validator

## Features

* Validates composer.json against having `dev-*` as dependency version
    * `dev-master` could is prohibited by default, but could be excluded
* Checks locker for presence (optional) and freshness

## Initial purpose

* Various commit hooks

## Installation

### Easy

Grab latest phar from [GitHub releases page](https://github.com/bankiru/composer-dev-branch-validator/releases)

### Manual

```bash
create-project bankiru/composer-dev-branch-validator ./checker/
cd checker/
composer install
php -dphar.readonly=0 bin/compiler.php
```

You will get latest `build/checker.phar` ready for use.

## Usage

```sh
# Implicit
php checker.phar path/to/project
# Excplicit
php checker.phar path/to/project/composer.json
# Workdir
cd path/to/project
php path/to/checker.phar
```

### Options

```sh
Usage:
  check [options] [--] [<path>]

Arguments:
  path                       Root package path [default: "./"]

Options:
  -l, --no-lock-check        Skip checking lock file
  -m, --ignore-missing-lock  Skip checking lock file
  -t, --allow-dev-master     Allow master-like branches (trunk, etc)
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
```
