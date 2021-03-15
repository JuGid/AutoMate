<?php

namespace Automate\Scenario;

use Automate\AutoMateDispatcher;
use Automate\AutoMateEvents;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Automate\Configuration\Configuration;
use Automate\Driver\DriverConfiguration;
use Automate\Driver\DriverManager;
use Automate\Exception\CommandException;
use Automate\Exception\LogException;
use Automate\Handler\ErrorHandler;
use Automate\Logs\AbstractLogger;
use Automate\Logs\DefaultLogger;
use Automate\Logs\LogType;
use Automate\Specification\Specification;

/**
 * @codeCoverageIgnore
 */
final class Runner
{
    public const VERBOSE_ALL = 2;
    public const VERBOSE_REPORT_ONLY = 1;
    public const VERBOSE_NONE = 0;

    /**
     * @var AutoMateDispatcher
     */
    private $dispatcher = null;

    /**
     * Specify if the runner is running with a specification
     *
     * @var bool
     */
    private $runningWithSpec = false;

    /**
     * @var AbstractLogger
     */
    private $logger = null;

    /**
     * @var array
     */
    private $currentDataset = [];

    /**
     * @var bool
     */
    private $testMode = false;
    
    /**
     * @var RemoteWebDriver|null
     */
    private $driver = null;

    /**
     * @var ErrorHandler
     */
    private $errorHandler = null;

    public function __construct(
        string $browser,
        bool $testMode = false,
        DriverConfiguration $driverConfiguration = null,
        AutoMateDispatcher $dispatcher = null
    ) {
        $this->driver = DriverManager::getDriver($browser, $driverConfiguration);
        $this->testMode = $testMode;
        $this->errorHandler = new ErrorHandler();
        $this->dispatcher = $dispatcher;
    }

    /**
     * To run a scenario with a specification
     */
    public function runSpecification(Scenario $scenario, Specification $specification) : void
    {
        $this->dispatcher->notify(AutoMateEvents::RUNNER_SPEC_BEGIN, []);

        $this->runningWithSpec = true;
        $this->errorHandler->shouldStoreDataset();
        
        if (Configuration::get('logs.enable') === true) {
            try {
                $this->logger = new DefaultLogger($specification->getColumnsHeader(), $scenario->getName());
            } catch (LogException $e) {
                $this->driver->quit();
                throw $e;
            }
        }
        
        foreach ($specification as $line) {
            $spec_line_data = [
                'currentRow'=>$specification->key() + 1,
                'totalRow' => $specification->getRowNumber() - 1,
                'line' => $line
            ];

            $this->dispatcher->notify(AutoMateEvents::RUNNER_SPEC_LINE, $spec_line_data);
            $this->currentDataset = $line;
            $this->runSimpleScenario($scenario);
        }

        $this->logger->end();
        
        if (!$this->testMode) {
            $specification->setProcessed();
        }
        
        $spec_end_data = [
            'errorHandler' => $this->getErrorHandler(),
            'winFilepath' => $this->logger->getFilepath(LogType::LOG_WINS),
            'errorFilepath' => $this->logger->getFilepath(LogType::LOG_ERRORS),
            'testMode' => $this->testMode
        ];

        $this->dispatcher->notify(AutoMateEvents::RUNNER_SPEC_END, $spec_end_data);

        $this->driver->quit();
    }

    /**
     * To run a simple scenario without specification and without logs if it's
     * called like $runner->runSimpleScenario(...)
     */
    public function runSimpleScenario(Scenario $scenario) : void
    {
        if (!$this->runWithSpecification()) {
            $this->dispatcher->notify(AutoMateEvents::RUNNER_SIMPLE_BEGIN, []);
        }

        try {
            foreach ($scenario as $step) {
                $data = ['driver'=> $this->driver, 'step'=>$step];
                $received = $this->dispatcher->notify(AutoMateEvents::STEP_TRANSFORM, $data);

                if (!$received) {
                    throw new CommandException('The command '. array_keys($step)[0] . ' does not exist');
                }
            }

            $this->errorHandler->win();

            $this->dispatcher->notify(AutoMateEvents::RUNNER_WIN, []);

            if ($this->runWithSpecification()) {
                $this->logger->log($this->getCurrentDataset(), LogType::LOG_WINS);
            }
        } catch (\Exception $e) {
            $this->errorHandler->error($e->getMessage(), get_class($e), $this->getCurrentDataset());

            $this->dispatcher->notify(AutoMateEvents::RUNNER_ERROR, ['exception'=>$e]);

            if ($this->runWithSpecification()) {
                $this->logger->addMessage($e->getMessage());
                $this->logger->log($this->getCurrentDataset(), LogType::LOG_ERRORS);
            }
        }

        if (!$this->runWithSpecification()) {
            $simple_end_data =  ['errorHandler'=> $this->getErrorHandler(),'testMode'=>$this->testMode];
            $this->dispatcher->notify(AutoMateEvents::RUNNER_SIMPLE_END, $simple_end_data);
            $this->driver->quit();
        }
    }

    public function runWithSpecification() : bool
    {
        return $this->runningWithSpec;
    }

    public function getCurrentDataset() : array
    {
        return $this->currentDataset;
    }

    public function getErrorHandler() : Errorhandler
    {
        return $this->errorHandler;
    }
}
