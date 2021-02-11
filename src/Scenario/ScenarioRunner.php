<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Driver\DriverManager;
use Automate\Handler\SpecVariableHandler;
use Automate\Specification\Specification;
use Automate\Specification\SpecificationFinder;

/**
 * @todo watch if hasSpecification is avoidable
 */
class ScenarioRunner {
  private $config;
  private $driverManager;
  private $spec;

  public function __construct() {
    $this->config = new Configuration();
    $this->driverManager = new DriverManager();
    $this->spec = null;
  }

  /**
   * Run the scenario.
   * If you set $with_spec and did not use ScenarioRunner::setSpecification
   * please watch SpecificationFinder::find documentation
   * @param string $scenario The scenario name. It corresponds to the yaml file name in scenario folder specified in config file.
   * @param bool $with_spec Specify if you want to run the scenario with a spec
   * @param string|null $on_browser You can specify a browser. If not, the default browser in config file is taken.
   * @return void
   */
  public function run(string $scenario_name, bool $with_spec = false, string $on_browser = null) : void{
    $scenarioFilepath = $this->config->getScenarioFolder() . '/' . $scenario_name . '.yaml';
    $scenario = new Scenario($scenarioFilepath, $scenario_name);
    $scenarioBrowser = $scenario->getScenarioBrowser($on_browser, $this->config->getDefaultBrowser());
    
    try {
      $driver = $this->driverManager->getDriver($scenarioBrowser, $this->config->getWebdriverFolder($scenarioBrowser));
      $sttr = new StepTransformer($driver);

      if($with_spec) {
        $this->runSpecification($sttr, $scenario);
      } else {
        $this->runScenario($sttr, $scenario);
      }

    } catch(\Exception $e) {
      echo $e->getMessage();
    }

    $driver->quit();
  }

  /**
   * Run a simple scenario, without any specification. [spec] scope is not usable
   */
  public function runScenario(StepTransformer $sttr, Scenario $scenario) {
    foreach($scenario as $step){
      $sttr->transform($step);
    }
  }

  /**
   * Run a scenario with a specification.
   * If the specification is not provided, Automate try to find a valid one
   * Each line is injected in [spec] variable scope and each line the scenario
   * start again from the begining
   * @see Automate\Specification\SpecificationFinder
   */
  public function runSpecification(StepTransformer $sttr, Scenario $scenario) {
    $finder = new SpecificationFinder();
    if($this->spec == null) {
      $this->spec = $finder->find($this->config->getSpecFolder(), $scenario->getName());
    }

    foreach($this->spec as $dataset) 
    {
      SpecVariableHandler::load($dataset);
      $this->runScenario($sttr, $scenario);
    }

    $this->spec->isProcessed();
  }

  public function setConfigurationFile(string $configFile) {
    $this->config->setConfigurationFile($configFile);
  }

  public function setScenarioFolder(string $scenarioFolder) {
    $this->config->setScenarioFolder($scenarioFolder);
  }

  public function setSpecification(Specification $spec) {
    $this->spec = $spec;
  }

}
