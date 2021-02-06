<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Driver\DriverManager;

class ScenarioRunner {
  private $configuration;
  private $driverManager;

  public function __construct() {
    $this->configuration = new Configuration();
    $this->driverManager = new DriverManager();
    $this->driverManager->setConfiguration($this->configuration);
  }

  public function run(string $scenario, ?string $on_browser) : void{
    $scenarioFilepath = $this->configuration->getScenarioFolder() . '/' . $scenario . '.yaml';
    $scenario = new Scenario($scenarioFilepath, $this->configuration);

    if($on_browser == null) {
      $driver = $this->driverManager->getDriver($scenario->getScenarioBrowser());
    } else {
      $driver = $this->driverManager->getDriver($on_browser);
    }
    $stepTransformer = new StepTransform($driver);

    foreach($scenario as $step){
      $stepTransformer->transform($step);
    }

    //$driver->quit();

    //$driver->get('https://en.wikipedia.org/wiki/Selenium_(software)');

  }
}
