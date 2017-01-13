<?php

$loader = require __DIR__.'/../vendor/autoload.php';

use Bankiru\Tools\BranchChecker\CheckerCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

$input = new ArgvInput();

$application = new Application();

$command = new CheckerCommand();

$application->add($command);
$application->setDefaultCommand($command->getName(), true);
$application->run($input);
