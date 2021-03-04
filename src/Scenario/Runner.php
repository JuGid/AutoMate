<?php 

namespace Automate\Scenario;

use Automate\AutoMateDispatcher;
use Automate\AutoMateEvents;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\DriverConfiguration;
use Automate\Driver\DriverManager;
use Automate\Exception\LogException;
use Automate\Handler\ErrorHandler;
use Automate\Logs\AbstractLogger;
use Automate\Logs\DefaultLogger;
use Automate\Logs\LogType;
use Automate\Specification\Specification;

/**
 * @codeCoverageIgnore
 */
class Runner {

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

    public function __construct(string $browser, bool $testMode = false, DriverConfiguration $driverConfiguration = null) {
        $this->driver = DriverManager::getDriver($browser, $driverConfiguration);
        $this->testMode = $testMode;
        $this->errorHandler = new ErrorHandler();
        $this->dispatcher = new AutoMateDispatcher();
    }

    /**
     * To run a scenario with a specification
     */
    public function runSpecification(Scenario $scenario, Specification $specification) : void {
        $this->runningWithSpec = true;
        $this->errorHandler->shouldStoreDataset();

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
            
        Console::endSpecification(
            $this->errorHandler,
            $this->logger->getFilepath(LogType::LOG_WINS),
            $this->logger->getFilepath(LogType::LOG_ERRORS),
            $this->testMode
        );
        
        $this->driver->quit();
    }

    /**
     * To run a simple scenario without specification and without logs if it's
     * called like $runner->runSimpleScenario(...)
     */
    public function runSimpleScenario(Scenario $scenario) : void {
        if(!$this->runWithSpecification()) Console::writeBegining();

        try {
            foreach($scenario as $step){
                $data = ['driver'=> $this->driver, 'step'=>$step];
                $this->dispatcher->notify(AutoMateEvents::STEP_TRANSFORM, $data);
            }

            $this->errorHandler->win();

            if($this->runWithSpecification()) {
                $this->logger->log($this->getCurrentDataset(), LogType::LOG_WINS);
            }

        } catch(\Exception $e) {
            $this->errorHandler->error($e->getMessage(), $this->getCurrentDataset());

            if($this->runWithSpecification()) {
                $this->logger->addMessage($e->getMessage());
                $this->logger->log($this->getCurrentDataset(), LogType::LOG_ERRORS);
            }
            Console::writeEx($e);
        }

        if(!$this->runWithSpecification()) {
            Console::endSimple($this->errorHandler, $this->testMode);
            $this->driver->quit();
        }
    }

    public function runWithSpecification() : bool {
        return $this->runningWithSpec;
    }

    public function getCurrentDataset() : array {
        return $this->currentDataset;
    }

    public function getErrorHandler() : Errorhandler {
        return $this->errorHandler;
    }
}