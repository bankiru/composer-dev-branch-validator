<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 10:34
 */

namespace Bankiru\Tools\BranchChecker;

use Composer\Command\Command;
use Composer\Factory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckerCommand extends Command
{

    protected function configure()
    {
        $this->setName('check');
        $this->addArgument('path', InputArgument::REQUIRED, 'Root package path');
        $this->addOption('no-lock-check', 'l', InputOption::VALUE_NONE, 'Skip checking lock file');
        $this->addOption('ignore-missing-lock', 'm', InputOption::VALUE_NONE, 'Skip checking lock file');
        $this->addOption('allow-dev-master', 't', InputOption::VALUE_NONE, 'Allow master-like branches (trunk, etc)');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setDecorated(true);
        $errors = [];

        $path                 = realpath($input->getArgument('path'));
        $composerJsonFilename = realpath($path . '/composer.json');

        $io       = $this->getIO();
        $composer = Factory::create($io, $composerJsonFilename);

        if (!$input->getOption('no-lock-check')) {
            $locker = $composer->getLocker();

            if (!$locker->isLocked() && !$input->getOption('ignore-missing-lock')) {
                $errors[] = 'Lock file missing';
            }
            if ($locker->isLocked() && !$locker->isFresh()) {
                $errors[] = 'Lock file hash invalid';
            }
        }

        $packageValidator = new PackageValidator();

        $errors = array_merge(
            $errors,
            $packageValidator->validatePackage($composer->getPackage(), $input->getOption('allow-dev-master'))
        );

        if (!$output->isQuiet()) {
            foreach ($errors as $error) {
                $output->writeln(sprintf('<comment>%s</comment>', $error));
            }
        }

        return count($errors) > 0 ? 1 : 0;
    }
}
