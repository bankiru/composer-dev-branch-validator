<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 10:30
 */

namespace Bankiru\Tools\BranchChecker;

class Checker
{
    /** @var  LockChecker */
    private $lockChecker;
    /** @var  PackageChecker */
    private $packageChecker;

    /**
     * Checker constructor.
     *
     * @param PackageChecker $packageChecker
     * @param LockChecker    $lockChecker
     */
    public function __construct(PackageChecker $packageChecker, LockChecker $lockChecker)
    {
        $this->packageChecker = $packageChecker;
        $this->lockChecker    = $lockChecker;
    }

    public function checkProject($projectPath)
    {
        if (!is_dir($projectPath)) {
            throw  new \RuntimeException($projectPath . ' is not a valid path');
        }

        $composerJsonFilename = $projectPath . '/composer.json';
        $composerLockFilename = $projectPath . '/composer.lock';

        $this->packageChecker->checkJsonFile($composerJsonFilename);
        $this->lockChecker->checkLockFile($composerLockFilename);
    }
}
