<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 10:44
 */

namespace Bankiru\Tools\BranchChecker;

class NullLockChecker extends LockChecker
{
    public function checkLockFile($filename)
    {
        return true;
    }
}
