<?php

namespace Automate\Scenario;

use Automate\Handler\ScenarioVariableHandler;
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
    private $name;

    public function __construct(string $file, string $name) {
        $this->scenario = $this->parseScenarioFile($file);
        $this->step = 0;
        $this->name = $name;

        if(isset($this->scenario['variables'])) {
            $variables = $this->scenario['variables'];

            $limit = count(array_keys($variables));
            for($i=0;$i<$limit; $i++) {
                ScenarioVariableHandler::add(array_keys($variables)[$i], array_values($variables)[$i]);
            }
            
        }
    }

    /**
     * @todo add scenario validation
     */
    private function parseScenarioFile(string $file) : array{
        try{
            return Yaml::parseFile($file);
        } catch(ParseException $e) {
            $message = sprintf("%s%s",$e->getMessage(), " Please check your configuration file.");
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
     * Configuration default browser has to be set in config file
     */
    public function getScenarioBrowser(?string $default_function, string $default_config) : string {
        if(!isset($this->scenario['browser'])) {
            return $default_function !== null ? $default_function : $default_config;
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