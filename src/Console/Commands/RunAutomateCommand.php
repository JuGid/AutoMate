<?php

namespace Automate\Console\Commands;

use Automate\AutoMate;
use Automate\Driver\DriverConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 */
class RunAutomateCommand extends Command
{
    protected static $defaultName = 'run';

    protected function configure()
    {
        $this->setDescription('Run AutoMate')
             ->setHelp("This command allow you to run AutoMate with simple options like server url or test mode")
             ->addOption('scenario', 's', InputOption::VALUE_REQUIRED, 'Scenario name')
             ->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Configuration file path')
             ->addOption('browser', 'b', InputOption::VALUE_OPTIONAL, 'Browser to run the scenario', '')
             ->addOption('headless', 'l', InputOption::VALUE_OPTIONAL, 'Enable running the browser driver in headless mode', false)
             ->addOption('server', 'a', InputOption::VALUE_OPTIONAL, 'Change the server url to run AutoMate')
             ->addOption('testMode', 't', InputOption::VALUE_OPTIONAL, 'Enable running AutoMate with testMode', false)
             ->addOption('specMode', 'm', InputOption::VALUE_OPTIONAL, 'Enable running AutoMate with specification', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $automate = new AutoMate($input->getOption('config'));

        $dv = new DriverConfiguration();

        $browser = $input->getOption('browser');
        if (null === $browser) {
            $browser = '';
        }

        $headless = $input->getOption('headless');
        if (null === $headless || true === $headless) {
            $dv->headlessMode();
        }

        $server = $input->getOption('server');
        if ($server !== null && is_string($server)) {
            $dv->setServerUrl($input->getOption('server'));
        }

        $testMode = $input->getOption('testMode');
        if (null === $testMode) {
            $testMode = true;
        }

        $specMode = $input->getOption('specMode');
        if (null === $specMode) {
            $specMode = true;
        }
        
        $automate->setDriverConfiguration($dv);
        $automate->run($input->getOption('scenario'), $specMode, $testMode, $browser);
        return Command::SUCCESS;
    }
}
