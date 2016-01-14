<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 11:00
 */

namespace Bankiru\Tools\BranchChecker;

use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\Constraint\MultiConstraint;

class ConstraintAccessor extends MultiConstraint
{
    /** @var  MultiConstraint */
    private $multiConstraint;

    /**
     * ConstraintAccessor constructor.
     *
     * @param MultiConstraint $multiConstraint
     */
    public function __construct(MultiConstraint $multiConstraint) { $this->multiConstraint = $multiConstraint; }

    /** @return ConstraintInterface[] */
    public function getConstraints() { return $this->multiConstraint->constraints; }
}
