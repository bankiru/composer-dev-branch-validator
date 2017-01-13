<?php

const ROOT_DIR  = __DIR__ . '/../';
const BUILD_DIR = ROOT_DIR . '/build/';

if (!@mkdir(BUILD_DIR) && !is_dir(BUILD_DIR)) {
    throw new \RuntimeException('Cannot create build dir');
}

// create with alias "project.phar"
$phar = new Phar(BUILD_DIR . 'checker.phar', 0, 'checker.phar');
$phar->buildFromDirectory(ROOT_DIR . '/', '/\.pem$/');
$phar->buildFromDirectory(ROOT_DIR . '/', '/\.php$/');
$phar->buildFromDirectory(ROOT_DIR . '/', '/\.json$/');
$phar->setStub($phar::createDefaultStub('bin/checker.php'));
