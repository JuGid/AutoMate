<?php 

namespace Automate;

use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\Proxy\HttpProxy;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use Automate\Specification\SpecificationFinder;
use Automate\Scenario\Scenario;
use Automate\Scenario\Runner;
use Automate\Driver\DriverConfiguration;

class AutoMate {

    /**
     * @var DriverConfiguration
     */
    private $driverConfiguration = null;

    public function __construct(string $configFile){
        Configuration::load($configFile);
    }

    /**
     * @codeCoverageIgnore
     */
    public function run(string $scenario_name, bool $withSpec = false, bool $testMode = false, string $onBrowser = '') : void {
        $scenario = null;
        $specification = null;
        $scenarioBrowser = '';
        $runner = null;
        VariableRegistry::set(Scope::WORLD, 'scenario', $scenario_name);
        
        try {
            $scenario = new Scenario($scenario_name);
            $scenarioBrowser = $scenario->getScenarioBrowser($onBrowser);
            $runner = new Runner($scenarioBrowser , $testMode, $this->driverConfiguration);
            
            if($withSpec) {
                $specification = (new SpecificationFinder())->find();
                $runner->runSpecification($scenario, $specification);

            } else {
                $runner->runSimpleScenario($scenario);
            }
        } catch(\Exception $e) {
            Console::writeEx($e);
        }
    }

    public function setDriverConfiguration(DriverConfiguration $driverConfiguration) {
        $this->driverConfiguration = $driverConfiguration;
    }

    public function getDriverConfiguration() : ?DriverConfiguration {
        return $this->driverConfiguration;
    }
}