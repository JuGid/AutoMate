<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Driver\DriverManager;
use Automate\Handler\WindowHandler;

class ScenarioRunner {
  private $configuration;
  private $driverManager;

  public function __construct() {
    $this->configuration = new Configuration();
    $this->driverManager = new DriverManager();
  }

  /**
   * @param string $scenario The scenario name. It corresponds to the yaml file name in scenario folder specified in config file.
   * @param string|null $on_browser You can specify a browser. If not, the default browser in config file is taken.
   * @return void
   */
  public function run(string $scenario, ?string $on_browser) : void{
    $scenarioFilepath = $this->configuration->getScenarioFolder() . '/' . $scenario . '.yaml';
    $scenario = new Scenario($scenarioFilepath, $this->configuration);

    $scenarioBrowser = $scenario->getScenarioBrowser();
    if($on_browser !== null) {
      $scenarioBrowser = $on_browser;
    }
    $driver = $this->driverManager->getDriver($scenarioBrowser, $this->configuration->getWebdriverFolder($scenarioBrowser));
    
    WindowHandler::setWindows($driver->getWindowHandles());

    $stepTransformer = new StepTransform($driver);
    try {
      foreach($scenario as $step){
        $stepTransformer->transform($step);
      }
    }catch(\Exception $e) {
      echo $e->getMessage();
    }

    $driver->quit();
  }

  public function setConfigurationFile(string $configFil) {
    $this->configuration->setConfigurationFile($configFil);
  }
}
