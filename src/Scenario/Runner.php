<?php 

namespace Automate\Scenario;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\DriverConfiguration;
use Automate\Driver\DriverManager;
use Automate\Exception\LogException;
use Automate\Logs\AbstractLogger;
use Automate\Logs\DefaultLogger;
use Automate\Logs\LogType;
use Automate\Specification\Specification;

/**
 * @codeCoverageIgnore
 */
class Runner {

    /**
     * If you run with a spec, errors counter
     * 
     * @var int
     */
    private $errors = 0;

    /**
     * If you run with a spec, wins counter
     * 
     * @var int
     */
    private $wins = 0;

    /**
     * 
     * @var StepTransformer
     */
    private $stepTransformer = null;

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

    public function __construct(string $browser, bool $testMode = false, DriverConfiguration $driverConfiguration = null) {
        $this->driver = DriverManager::getDriver($browser, $driverConfiguration);
        $this->stepTransformer = new StepTransformer($this->driver);
        $this->testMode = $testMode;
    }

    /**
     * To run a scenario with a specification
     */
    public function runSpecification(Scenario $scenario, Specification $specification) : void {
        $this->runningWithSpec = true;
        
        if(Configuration::get('logs.enable') === true) {
            try {
                $this->logger = new DefaultLogger($specification->getColumnsHeader(), $scenario->getName());
            } catch(LogException $e) {
                Console::writeEx($e);
                $this->driver->quit();
            } 
        }
        
        foreach($specification as $line) {
            Console::writeBeginingLine($specification->key() + 1, $specification->getRowNumber() - 1, $line);
            
            $this->currentDataset = $line;
            $this->runSimpleScenario($scenario);
        }

        $this->logger->end();
        
        if(!$this->testMode) $specification->setProcessed();
        
        Console::writeEndingSpecification(
            $this->wins, 
            $this->errors,
            $this->logger->getFilepath(LogType::LOG_WINS),
            $this->logger->getFilepath(LogType::LOG_ERRORS)
        );

        $this->driver->quit();
    }

    /**
     * To run a simple scenario without specification and without logs if it's
     * called like $runner->runSimpleScenario(...)
     */
    public function runSimpleScenario(Scenario $scenario) : void {
        try {
            foreach($scenario as $step){
                $this->getStepTransformer()->transform($step);
            }

            if($this->runWithSpecification()) {
                $this->logger->log($this->getCurrentDataset(), LogType::LOG_WINS);
                ++$this->wins;
            }

        } catch(\Exception $e) {
            if($this->runWithSpecification()) {
                $this->logger->addMessage($e->getMessage());
                $this->logger->log($this->getCurrentDataset(), LogType::LOG_ERRORS);
                ++$this->errors;
              }
              Console::writeEx($e);
        }

        if(!$this->runWithSpecification()) $this->driver->quit();
    }

    public function getStepTransformer() : StepTransformer {
        return $this->stepTransformer;
    }

    public function runWithSpecification() : bool {
        return $this->runningWithSpec;
    }

    public function getCurrentDataset() : array {
        return $this->currentDataset;
    }

}