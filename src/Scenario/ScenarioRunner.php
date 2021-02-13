<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Driver\DriverManager;
use Automate\Exception\BrowserException;
use Automate\Exception\DriverException;
use Automate\Exception\LogException;
use Automate\Exception\SpecificationException;
use Automate\Logs\AbstractLogger;
use Automate\Logs\DefaultLogger;
use Automate\Logs\LogType;
use Automate\Specification\Specification;
use Automate\Specification\SpecificationFinder;

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
   * @var DefaultLogger
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
   * @param string $scenario The scenario name. It corresponds to the yaml file name in scenario folder specified in config file.
   * @param bool $with_spec Specify if you want to run the scenario with a spec
   * @param string|null $on_browser You can specify a browser. If not, the default browser in scenario or config file is taken.
   * @return void
   * 
   * @see Automate\Scenario\Scenario::getScenarioBrowser()
   */
  public function run(string $scenario_name, bool $with_spec = false, string $on_browser = null) : void{
    $scenarioFilepath = $this->config->getScenarioFolder() . '/' . $scenario_name . '.yaml';
    $scenario = new Scenario($scenarioFilepath, $scenario_name);
    $scenarioBrowser = $scenario->getScenarioBrowser($on_browser, $this->config->getDefaultBrowser());

    try {
      $driver = $this->driverManager->getDriver($scenarioBrowser, $this->config->getWebdriverFolder($scenarioBrowser));
      $sttr = new StepTransformer($driver);
    } catch(BrowserException|DriverException $e) {
      echo $e->getMessage() . "\n";
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
  private function runScenario(StepTransformer $sttr, Scenario $scenario, array $dataset = []) {
    try 
    {
      foreach($scenario as $step){
        $sttr->transform($step);
      }

      if($this->hasSpec()) {
        $this->logger->log($dataset, LogType::LOG_WINS);
        ++$this->wins;
      }
      
    } catch(\Exception $e) 
    {
      if($this->hasSpec()) {
        $this->logger->addMessage($e->getMessage());
        $this->logger->log($dataset, LogType::LOG_ERRORS);
        ++$this->errors;
      }
      echo $e->getMessage() . "\n";
    } 
  }

  /**
   * Run a scenario with a specification.
   * If the specification is not provided, Automate try to find a valid one
   * Each line is injected in [spec] variable scope and each line the scenario
   * start again from the begining
   * @see Automate\Specification\SpecificationFinder
   */
  private function runSpecification(StepTransformer $sttr, Scenario $scenario) {
    try {
      $finder = new SpecificationFinder();
      if(!$this->hasSpec()) {
        $this->spec = $finder->find($this->config->getSpecFolder(), $scenario->getName());
        echo 'Detected file : ' . $this->spec->getFilepath() . "\n";
        $this->logger->init($this->config->getLogsFolder(), $scenario->getName(), $this->spec->getColumnsHeader());
      }
    } catch(SpecificationException|LogException $e) {
      echo $e->getMessage() . "\n";
      $this->driverManager->getCurrentDriver()->quit();
      die();
    }
    
    

    foreach($this->spec as $dataset) 
    {
      echo "_________________________________________________________________\n";
      echo "    /\        | |      |  \/  |     | |      \n";
      echo "   /  \  _   _| |_ ___ | \  / | __ _| |_ ___ \n";
      echo "  / /\ \| | | | __/ _ \| |\/| |/ _` | __/ _ \ \n";
      echo " / ____ \ |_| | || (_) | |  | | (_| | ||  __/ \n";
      echo "/_/    \_\__,_|\__\___/|_|  |_|\__,_|\__\___| \n";
      echo "Line ". ($this->spec->key() + 1) .'/'. $this->spec->getRowNumber() . "\n";
      echo "Data set : " . implode(',',$dataset) . "\n";
      echo "_________________________________________________________________\n";
      $this->runScenario($sttr, $scenario, $dataset);
    }

    if(!$this->testMode) $this->spec->setProcessed();
    $this->logger->end();
    echo "_________________________________________________________________\n";
    echo "___ _  _ ___  \n";
    echo "| __| \| |   \ \n";
    echo "| _|| .` | |) |\n";
    echo "|___|_|\_|___/    \n";
    echo 'Scenario with specification finished with Wins : '.$this->wins . ' / Errors : '. $this->errors . "\n";
    echo "Logs can be found at : \n";
    echo "* LOGS_WIN : " . $this->logger->getConfiguration()->getFilepathLogWins() . "\n";
    echo "* LOGS_ERRORS : " . $this->logger->getConfiguration()->getFilepathLogErrors() . "\n";
    echo "=================================================================\n";
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
  public function setConfigurationFile(string $configFile) {
    $this->config->setConfigurationFile($configFile);
  }

  /**
   * You can programmatically change the scenario folder
   * 
   * @param string $scenarioFolder
   */
  public function setScenarioFolder(string $scenarioFolder) {
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
  public function hasSpec() : bool {
    return $this->spec !== null;
  }

  /**
   * Set the test mode to true
   * 
   * The efefct is that the specification file is not marked as _PROCESSED
   */
  public function enableTestMode() {
    $this->testMode = true;
  }

  public function setColumnsToLog(array $columns) {
    $this->logger->getConfiguration()->setlogColumns($columns);
  }

}
