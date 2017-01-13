<?php

namespace Bankiru\Tools\BranchChecker;

use Composer\Package\PackageInterface;
use Composer\Package\Version\VersionParser;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\MultiConstraint;

final class PackageValidator
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
        $devMaster     = new Constraint('==', $versionParser->normalize('dev-master'));

        foreach ($package->getRequires() as $link) {
            $linkConstraint = $link->getConstraint();

            if (preg_match(PlatformRepository::PLATFORM_PACKAGE_REGEX, $link->getTarget())) {
                continue;
            }

            if ($linkConstraint->matches($devMaster)) {
                if ($allowDevMaster) {
                    continue;
                }

                $errors[] = sprintf(
                    'Package "%s" is required with branch constraint %s',
                    $link->getTarget(),
                    $linkConstraint->getPrettyString()
                );
            }

            $constraints = [$linkConstraint];

            if ($linkConstraint instanceof MultiConstraint) {
                $constraints = $linkConstraint->getConstraints();
            }

            foreach ($constraints as $constraint) {
                if (0 === strpos($constraint->getPrettyString(), 'dev-')) {
                    $errors[] = sprintf(
                        'Package "%s" is required with branch constraint %s',
                        $link->getTarget(),
                        $linkConstraint->getPrettyString()
                    );
                }
            }
        }

        return $errors;
    }
}
