<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\DriverManager;
use Automate\Driver\Proxy;
use Automate\Exception\BrowserException;
use Automate\Exception\ConfigurationException;
use Automate\Exception\DriverException;
use Automate\Exception\LogException;
use Automate\Exception\SpecificationException;
use Automate\Handler\GlobalVariableHandler;
use Automate\Logs\AbstractLogger;
use Automate\Logs\DefaultLogger;
use Automate\Logs\LogType;
use Automate\Specification\Specification;
use Automate\Specification\SpecificationFinder;
use PASVL\Validation\Problems\ArrayFailedValidation;
use Symfony\Component\Yaml\Exception\ParseException;

class ScenarioRunner {
  /**
   * @var Configuration
   */
  private $config;

  /**
   * @var DriverManager
   */
  private $driverManager;

  /**
   * @var Specification
   */
  private $spec = null;

  /**
   * @var AbstractLogger
   */
  private $logger;

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
   * Test mode
   * 
   * @var bool
   */
  private $testMode = false;

  public function __construct() {
      $this->config = new Configuration();
      $this->driverManager = new DriverManager();
      $this->logger = new DefaultLogger();
  }

  /**
   * Run the scenario.
   * If you set $with_spec and did not use ScenarioRunner::setSpecification
   * please watch SpecificationFinder::find documentation
   * @param string $scenario_name The scenario name. It corresponds to the yaml file name in scenario folder specified in config file.
   * @param bool $with_spec Specify if you want to run the scenario with a spec
   * @param string|null $on_browser You can specify a browser. If not, the default browser in scenario or config file is taken.
   * @return void
   * 
   * @see Automate\Scenario\Scenario::getScenarioBrowser()
   */
  public function run(string $scenario_name, bool $with_spec = false, string $on_browser = null) : void{
    $scenarioFilepath = $this->config->getScenarioFolder() . '/' . $scenario_name . '/main.yaml';
    Console::writeln('Scenario file : ' . $scenarioFilepath);
    try 
    {
      $scenario = new Scenario($scenarioFilepath, $scenario_name);
      GlobalVariableHandler::setScenarioName($scenario_name);

      $scenarioBrowser = $scenario->getScenarioBrowser(
        $on_browser, 
        $this->config->getDefaultBrowser()
      );
      
      $driver = $this->driverManager->getDriver(
        $scenarioBrowser, 
        $this->config->getWebdriverFolder($scenarioBrowser)
      );

      $sttr = new StepTransformer($driver);
    } 
    catch(BrowserException|DriverException|ParseException $e) {
      Console::writeEx($e);
      die();
    }
    
    if($with_spec) {
      $this->runSpecification($sttr, $scenario);
    } else {
      $this->runScenario($sttr, $scenario);
    }

    $this->driverManager->getCurrentDriver()->quit();
  }

  /**
   * Run a simple scenario, without any specification. [spec] scope is not usable
   */
  private function runScenario(StepTransformer $sttr, Scenario $scenario, array $dataset = []) : void {
    try 
    {
      foreach($scenario as $step){
        $sttr->transform($step);
      }

      if($this->hasSpec()) {
        $this->logger->log($dataset, LogType::LOG_WINS);
        ++$this->wins;
      }
      
    } 
    catch(\Exception $e) 
    {
      if($this->hasSpec()) {
        $this->logger->addMessage($e->getMessage());
        $this->logger->log($dataset, LogType::LOG_ERRORS);
        ++$this->errors;
      }
      Console::writeEx($e);
    } 
  }

  /**
   * Run a scenario with a specification.
   * If the specification is not provided, Automate try to find a valid one
   * Each line is injected in [spec] variable scope and each line the scenario
   * start again from the begining
   * @see Automate\Specification\SpecificationFinder
   */
  private function runSpecification(StepTransformer $sttr, Scenario $scenario) : void {
    try {
      $finder = new SpecificationFinder();
      if(!$this->hasSpec()) {
        $this->spec = $finder->find($this->config->getSpecFolder());
        echo 'Detected file : ' . $this->spec->getFilepath() . "\n";
        
        if($this->config->isLogEnable()) {
          $this->logger->init(
            $this->config->getLogsFolder(), 
            $this->spec->getColumnsHeader()
          );
        } else {
          $this->logger->disable();
        }
        
      }
    } catch(SpecificationException|LogException $e) {
      Console::writeEx($e);
      $this->driverManager->getCurrentDriver()->quit();
      die();
    }
    
    foreach($this->spec as $dataset) 
    {
      Console::separator();
      Console::logo();
      Console::writeln("Line ". ($this->spec->key() + 1) .'/'. $this->spec->getRowNumber());
      Console::writeln("Data set : " . implode(',',$dataset));
      Console::separator();

      $this->runScenario($sttr, $scenario, $dataset);
    }

    if(!$this->testMode) $this->spec->setProcessed();
    
    $this->logger->end();

    Console::end();
    Console::writeln('Scenario with specification finished with Wins : '.$this->wins . ' / Errors : '. $this->errors);
    Console::separator('=');
    Console::writeln("Logs can be found at :");
    Console::writeln("* LOGS_WIN : " . $this->logger->getConfiguration()->getFilepathLogWins());
    Console::writeln("* LOGS_ERRORS : " . $this->logger->getConfiguration()->getFilepathLogErrors());
    Console::separator('=');
  }

  /**
   * You can set a new configuration file. Create it and provide filepath
   * This configuration file must be a valid configuration file
   * 
   * <code>
   *  $scenarioRunner = new ScenarioRunner();
   *  $scenarioRunner->setConfigurationFile(__DIR__.'/config/config-jugid.yaml');
   *  $scenarioRunner->run('youtube', true);
   * </code>
   * 
   * @param string $configFile The filepath of the new configuration file
   */
  public function setConfigurationFile(string $configFile) : void {
    try {
      $this->config->setConfigurationFile($configFile);
    }catch(ConfigurationException $e) {
      Console::writeEx($e);
      die();
    }
  }

  /**
   * You can programmatically change the scenario folder
   * 
   * @param string $scenarioFolder
   */
  public function setScenarioFolder(string $scenarioFolder) : void {
    $this->config->setScenarioFolder($scenarioFolder);
  }

  /**
   * Set a Specification object manually
   * 
   * This can be use if you don't like the specification finder
   * 
   * @param Specification $spec
   * @see Automate\Specification\SpecificationFinder
   */
  public function setSpecification(Specification $spec) : void{
    $this->spec = $spec;
  }

  /**
   * Set a new logger. If you don't, the Automate\Logs\DefaultLogger is used
   * 
   * If you create your proper Logger, extends your logger from
   * Automate\Logs\AbstractLogger and pass it to this function
   * 
   * @param AbstractLogger $logger
   */
  public function setLogger(AbstractLogger $logger) : void{
    $this->logger = $logger;
  }

  /**
   * @return bool If the scenario runner spec is not null
   */
  private function hasSpec() : bool {
    return $this->spec !== null;
  }

  /**
   * Set the test mode to true
   * 
   * The efefct is that the specification file is not marked as _PROCESSED
   */
  public function enableTestMode() : void {
    $this->testMode = true;
  }

  public function setColumnsToLog(array $columns) : void {
    $this->logger->getConfiguration()->setlogColumns($columns);
  }

  public function runWithProxy(Proxy $proxy) {
    $this->config->setProxy($proxy);
  }

}
