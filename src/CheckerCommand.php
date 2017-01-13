<?php

namespace Bankiru\Tools\BranchChecker;

use Composer\Command\BaseCommand;
use Composer\Factory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CheckerCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('check');
        $this->addArgument('path', InputArgument::OPTIONAL, 'Root package path', './');
        $this->addOption('no-lock-check', 'l', InputOption::VALUE_NONE, 'Skip all lock checks');
        $this->addOption('ignore-missing-lock', 'm', InputOption::VALUE_NONE, 'Skip checking lock file existence');
        $this->addOption('allow-dev-master', 't', InputOption::VALUE_NONE, 'Allow master-like branches (trunk, etc)');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $output->setDecorated(true);
        $errors = [];

        $path = realpath($input->getArgument('path'));
        if (is_dir($path)) {
            $path .= '/composer.json';
        }

        $composerPath = realpath($path);

        if (false === $composerPath) {
            throw new \RuntimeException('Root package was not found in ' . $path);
        }

        $composer = Factory::create($this->getIO(), $composerPath);

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
            $packageValidator->validatePackage(
                $composer->getPackage(),
                $input->getOption('allow-dev-master')
            )
        );

        if (count($errors) === 0) {
            $io->success('No errors found');

            return 0;
        }

        $io->error($errors);

        return 1;
    }
}
