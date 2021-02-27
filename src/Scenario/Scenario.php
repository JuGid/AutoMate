<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Exception\ScenarioException;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use Iterator;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Scenario implements Iterator{

    /**
     * @var array
     */
    private $scenario;

    /**
     * @var int
     */
    private $step;

    /**
     * @var string
     */
    public $name;

    public function __construct(string $name , string $sub = 'main') {
        
        $this->scenario = $this->parseScenarioFile(sprintf('%s/%s/%s.yaml', 
                                    Configuration::get('scenario.folder'),
                                    $name,
                                    $sub)
                                );
        $this->step = 0;
        $this->name = $name;

        if(!isset($this->scenario['scenario']['steps'])) {
            throw new ScenarioException('You must define steps in your scenario file');
        }

        if(isset($this->scenario['variables'])) {
            $variables = $this->scenario['variables'];

            $limit = count(array_keys($variables));
            for($i=0;$i<$limit; $i++) {
                VariableRegistry::set(Scope::SCENARIO, array_keys($variables)[$i], array_values($variables)[$i]);
            }
        }
    }

    private function parseScenarioFile(string $file) : array{
        try{
            return Yaml::parseFile($file);
        } catch(ParseException $e) {
            $message = sprintf("%s%s",$e->getMessage(), "\nPlease check your scenario file and be sure to surround variable call with quotes.");
            throw new ParseException($message, $e->getParsedLine());
        }
    }

    public function getName() : string {
        return $this->name;
    }

    public function getScenarioArray() : array {
        return $this->scenario;
    }

    /**
     * Return a browser name depending on each browser name provided.
     * If all are set, priority is : scenario -> function -> configuration
     * Configuration default browser MUST be set in config file
     */
    public function getScenarioBrowser(?string $default_function) : string {
        if(!isset($this->scenario['browser'])) {
            return ($default_function !== null) && !empty($default_function) ? $default_function : Configuration::get('browser.default');
        } else {
            return $this->scenario['browser'];
        }
    }

    /**
     * @return void
     */
    public function rewind() {
        $this->step = 0;
    }

    /**
     * @return array
     */
    public function current() {
        return $this->scenario['scenario']['steps'][$this->step];
    }

    /**
     * @return string
     */
    public function key() {
        return key($this->scenario['scenario']['steps'][$this->step]);
    }

    /**
     * @return void
     */
    public function next() {
        ++$this->step;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->scenario['scenario']['steps'][$this->step]);
    }


}