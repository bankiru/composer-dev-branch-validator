<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 15:55
 */

namespace Bankiru\Tools\BranchChecker;

use Composer\Package\PackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\MultiConstraint;

class PackageValidator
{
    /**
     * @param PackageInterface $package
     * @param bool             $allowDevMaster
     *
     * @return \string[]
     */
    public function validatePackage(PackageInterface $package, $allowDevMaster = false)
    {
        $errors = [];

        $versionParser = new VersionParser();
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $devMaster = new Constraint('==', $versionParser->normalize('dev-master'));

        foreach ($package->getRequires() as $link) {

            $linkConstraint = $link->getConstraint();

            if ($linkConstraint->matches($devMaster)) {
                if ($allowDevMaster) {
                    continue;
                }

                $errors[] =
                    sprintf('Package "%s" is required with branch constraint %s',
                            $link->getTarget(),
                            $linkConstraint->getPrettyString());
            }


            $constraints = [$linkConstraint];

            if ($linkConstraint instanceof MultiConstraint) {
                $constraints = (new ConstraintAccessor($linkConstraint))->getConstraints();
            }

            foreach ($constraints as $constraint) {
                if ('dev-' === substr($constraint->getPrettyString(), 0, 4)) {
                    $errors[] =
                        sprintf('Package "%s" is required with branch constraint %s',
                                $link->getTarget(),
                                $linkConstraint->getPrettyString());
                }
            }
        }

        return $errors;
    }
}
