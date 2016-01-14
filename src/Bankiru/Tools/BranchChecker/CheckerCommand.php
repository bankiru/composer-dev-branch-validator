<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 14.01.2016
 * Time: 10:34
 */

namespace Bankiru\Tools\BranchChecker;

use Symfony\Component\Console\Command\Command;
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
        $packageChecker = new PackageChecker($input->getOption('allow-dev-master'));

        $lockChecker = new NullLockChecker();
        if (!$input->getOption('no-lock-check')) {
            $lockChecker = new LockChecker($input->getOption('ignore-missing-lock'));
        }

        $checker = new Checker($packageChecker, $lockChecker);

        $checker->checkProject(realpath($input->getArgument('path')));
    }
}
