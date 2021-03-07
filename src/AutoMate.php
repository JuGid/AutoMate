<?php 

namespace Automate;

use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use Automate\Specification\SpecificationFinder;
use Automate\Scenario\Scenario;
use Automate\Scenario\Runner;
use Automate\Driver\DriverConfiguration;
use Automate\Handler\ErrorHandler;

final class AutoMate {

    /**
     * @var DriverConfiguration
     */
    private $driverConfiguration = null;

    /**
     * 
     * @var AutoMateDispatcher
     */
    private $dispatcher;

    public function __construct(string $configFile){
        $this->dispatcher = new AutoMateDispatcher();
        $this->dispatcher->attachCoreListeners();
        Configuration::load($configFile);
    }

    /**
     * @codeCoverageIgnore
     * 
     * @return ErrorHandler|bool If the scenario has errors
     */
    public function run(string $scenario_name, bool $withSpec = false, bool $testMode = false, string $onBrowser = '') {
        VariableRegistry::reset(Scope::WORLD);
        VariableRegistry::set(Scope::WORLD, 'scenario', $scenario_name);
        $scenario = null;
        $specification = null;
        $scenarioBrowser = '';
        $runner = null;
        
        try {
            $scenario = new Scenario($scenario_name);
            $scenarioBrowser = $scenario->getScenarioBrowser($onBrowser);
            $runner = new Runner($scenarioBrowser , $testMode, $this->driverConfiguration, $this->dispatcher);
            
            $eventBegin = $withSpec ? AutoMateEvents::RUNNER_SPEC_BEGIN : AutoMateEvents::RUNNER_SIMPLE_BEGIN;
            $this->dispatcher->notify($eventBegin, []);

            if($withSpec) {
                $specification = (new SpecificationFinder())->find();
                $runner->runSpecification($scenario, $specification);
            } else {
                $runner->runSimpleScenario($scenario);
            }

            $eventEnd = $withSpec ? AutoMateEvents::RUNNER_SPEC_END : AutoMateEvents::RUNNER_SIMPLE_END;
            $this->dispatcher->notify($eventEnd, []);

            if($runner->getErrorHandler()->countErrors() > 0) {
                $this->dispatcher->notify(AutoMateEvents::RUNNER_ENDS_ERROR, [
                    'errors'=> $runner->getErrorHandler()->getErrors()
                ]);
            }
        } catch(\Exception $e) {
            Console::writeEx($e);
            return false;
        }

        return $runner->getErrorHandler();
    }

    public function setDriverConfiguration(DriverConfiguration $driverConfiguration) {
        $this->driverConfiguration = $driverConfiguration;
    }

    public function getDriverConfiguration() : ?DriverConfiguration {
        return $this->driverConfiguration;
    }

    /**
     * @param array|string $event Event or array of event to subsbribe on
     */
    public function registerPlugin($event, AutoMateListener $listener) {
        $this->dispatcher->attach($event, $listener);
    }
}