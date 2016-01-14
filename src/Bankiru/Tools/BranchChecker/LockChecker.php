<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 10:41
 */

namespace Bankiru\Tools\BranchChecker;

class LockChecker
{
    /** @var  bool */
    private $missingOk;

    /**
     * LockChecker constructor.
     *
     * @param bool $missingOk
     */
    public function __construct($missingOk = false) { $this->missingOk = (bool)$missingOk; }

    public function checkLockFile($filename)
    {
        if (!is_file($filename)) {
            if ($this->missingOk) {
                return true;
            }

            throw new \RuntimeException('No lock file present: ' . $filename);
        }

        return false;
    }
}
