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
php -dphar.readonly=0 bin/compiler.php
```

You will get latest `build/checker.phar` ready for use.


