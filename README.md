# Composer json and lock validator

## Features

* Validates composer.json against having `dev-*` as dependency version
    * `dev-master` could is prohibited by default, but could be excluded
* Checks locker for presence (optional) and freshness

## Initial purpose

* Various commit hooks

## Installation

Grab latest phar from GitHub releases page

OR

composer --install-project 


