<?php

$loader = require __DIR__.'/../vendor/autoload.php';

use Bankiru\Tools\BranchChecker\CheckerCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

$input = new ArgvInput();

$application = new Application();
$application->add(new CheckerCommand());
$application->run($input);
