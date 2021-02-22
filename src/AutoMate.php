<?php 

namespace Automate;

use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\Proxy\HttpProxy;
use Automate\Exception\BrowserException;
use Automate\Exception\ScenarioException;
use Automate\Exception\SpecificationException;
use Automate\Specification\SpecificationFinder;
use Automate\Scenario\Scenario;
use Automate\Scenario\Runner;
use Symfony\Component\Yaml\Exception\ParseException;

class AutoMate {

    /**
     * @var HttpProxy
     */
    private $httpProxy = null;

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
        
        try {
            $scenario = new Scenario($scenario_name);
            $scenarioBrowser = $scenario->getScenarioBrowser($onBrowser);
            $runner = new Runner($scenarioBrowser , $testMode, $this->httpProxy);
            
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

    public function setProxy(HttpProxy $httpProxy) {
        $this->httpProxy = $httpProxy;
    }
}