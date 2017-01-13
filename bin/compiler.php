<?php

define('CHECKER_ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..');
define('CHECKER_BUILD_DIR', CHECKER_ROOT_DIR . DIRECTORY_SEPARATOR . 'build');

if (!@mkdir(CHECKER_BUILD_DIR) && !is_dir(CHECKER_BUILD_DIR)) {
    throw new \RuntimeException('Cannot create build dir');
}

// create with alias "project.phar"
$phar = new Phar(CHECKER_BUILD_DIR . DIRECTORY_SEPARATOR . 'checker.phar', 0, 'checker.phar');
$phar->buildFromDirectory(CHECKER_ROOT_DIR . DIRECTORY_SEPARATOR, '/\.pem$/');
$phar->buildFromDirectory(CHECKER_ROOT_DIR . DIRECTORY_SEPARATOR, '/\.php$/');
$phar->buildFromDirectory(CHECKER_ROOT_DIR . DIRECTORY_SEPARATOR, '/\.json$/');
$phar->setStub($phar::createDefaultStub('bin' . DIRECTORY_SEPARATOR . 'checker.php'));
