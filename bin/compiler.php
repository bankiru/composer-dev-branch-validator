<?php

define('CHECKER_ROOT_DIR', __DIR__ . '/../');
define('CHECKER_BUILD_DIR', CHECKER_ROOT_DIR . '/build/');

if (!@mkdir(CHECKER_BUILD_DIR) && !is_dir(CHECKER_BUILD_DIR)) {
    throw new \RuntimeException('Cannot create build dir');
}

// create with alias "project.phar"
$phar = new Phar(CHECKER_BUILD_DIR . 'checker.phar', 0, 'checker.phar');
$phar->buildFromDirectory(CHECKER_ROOT_DIR . '/', '/\.pem$/');
$phar->buildFromDirectory(CHECKER_ROOT_DIR . '/', '/\.php$/');
$phar->buildFromDirectory(CHECKER_ROOT_DIR . '/', '/\.json$/');
$phar->setStub($phar::createDefaultStub('bin/checker.php'));
