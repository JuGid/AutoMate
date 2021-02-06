<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Iterator;
use Symfony\Component\Yaml\Yaml;

class Scenario implements Iterator{

    private $scenario;
    private $configuration;
    private $step;

    public function __construct(string $file, Configuration $configuration) {
        $this->scenario = Yaml::parseFile($file);
        $this->configuration = $configuration;
        $this->step = 0;
    }

    public function getScenarioArray() {
        return $this->scenario;
    }

    public function getScenarioBrowser() {
        return isset($this->scenario['browser']) ? $this->scenario['browser'] : $this->configuration->getDefaultBrowser();
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