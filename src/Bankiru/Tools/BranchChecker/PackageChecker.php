<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 10:41
 */

namespace Bankiru\Tools\BranchChecker;

use Composer\Package\Loader\ArrayLoader;
use Composer\Package\RootPackage;
use Composer\Package\Version\VersionParser;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\MultiConstraint;

class PackageChecker
{
    /** @var  bool */
    private $allowDevMaster;

    /**
     * PackageChecker constructor.
     *
     * @param bool $allowDevMaster
     */
    public function __construct($allowDevMaster = false) { $this->allowDevMaster = $allowDevMaster; }

    public function checkJsonFile($filename, $version = 'dev-master')
    {
        if (!is_file($filename)) {
            throw new \LogicException('No json file present at the package root');
        }

        $loader            = new ArrayLoader(new VersionParser());
        $config            = json_decode(file_get_contents($filename), true);
        $config['version'] = $version;
        $package           = $loader->load($config, RootPackage::class);

        $versionParser = new VersionParser();
        $devMaster     = new Constraint('==', $versionParser->normalize('dev-master'));

        foreach ($package->getRequires() as $link) {

            $linkConstraint = $link->getConstraint();

            if ($linkConstraint->matches($devMaster)) {
                if ($this->allowDevMaster) {
                    continue;
                }

                throw new \LogicException(
                    sprintf('Package "%s" is required with branch constraint %s',
                            $link->getTarget(),
                            $linkConstraint->getPrettyString())
                );
            }


            $constraints = [$linkConstraint];

            if ($linkConstraint instanceof MultiConstraint) {
                $constraints = (new ConstraintAccessor($linkConstraint))->getConstraints();
            }

            foreach ($constraints as $constraint) {
                if ('dev-' === substr($constraint->getPrettyString(), 0, 4)) {
                    throw new \LogicException(sprintf('Package "%s" is required with branch constraint %s',
                                                      $link->getTarget(),
                                                      $linkConstraint->getPrettyString()));
                }
            }
        }

        return true;
    }
}
