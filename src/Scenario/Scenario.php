<?php

namespace Automate\Scenario;

use Automate\Handler\ScenarioVariableHandler;
use Iterator;
use Symfony\Component\Yaml\Yaml;

class Scenario implements Iterator{

    private $scenario;
    private $step;

    public function __construct(string $file) {
        $this->scenario = Yaml::parseFile($file);
        $this->step = 0;

        if(isset($this->scenario['variables'])) {
            $variables = $this->scenario['variables'];

            $limit = count(array_keys($variables));
            for($i=0;$i<$limit; $i++) {
                ScenarioVariableHandler::add(array_keys($variables)[$i], array_values($variables)[$i]);
            }
            
        }
    }

    public function getScenarioArray() {
        return $this->scenario;
    }

    public function getScenarioBrowser() {
        return isset($this->scenario['browser']) ? $this->scenario['browser'] : null;
    }

    public function rewind() {
        $this->step = 0;
    }

    public function current() {
        return $this->scenario['scenario']['steps'][$this->step];
    }

    public function key() {
        return key($this->scenario['scenario']['steps'][$this->step]);
    }

    public function next() {
        ++$this->step;
    }

    public function valid() {
        return isset($this->scenario['scenario']['steps'][$this->step]);
    }


}