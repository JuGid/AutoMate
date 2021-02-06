<?php 

namespace Automate\Scenario;

use Facebook\WebDriver\WebDriverBy;

/**
 * @todo Améliorer cette classe de transformation toute pourrie
 */
class StepTransform {

    private $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function transform(array $step) : void{
        $transformerClass = __NAMESPACE__ . '\\Transformer\\' . ucfirst(key($step)) . 'Transformer';
        $validatorClass = __NAMESPACE__ . '\\Validator\\' . ucfirst(key($step)) . 'Validator';
        $transformer = new $transformerClass();
        $transformer->transform($this->driver, $step, new $validatorClass());

        $stepKey = key($step);
        if($stepKey == 'go') {
            $this->go($step['go']);
        } elseif($stepKey == 'fill') {
            $step = $step['fill'];
            $this->fill(array_keys($step)[0], array_values($step)[0], $step['with'], $step['then']);
        } elseif($stepKey == "click") { 
            //$this->click(key($step['click'][0]), $step['click'][0]);
        } elseif($stepKey == "wuntil") {
            echo 'wait_until' . "\n"; 
        } else {
            echo key($step)."\n";
        }
        
    }

    public function go(string $adresse) : void {
        $this->driver->get($adresse);
    }

    public function fill(string $typeSearch, string $element, string $with, ?string $then) : void {
        echo 'fill' . "\n";
    }

    public function click(string $typeSearch, string $element) : void {
        echo 'click' . "\n";
    }

    public function wait_until($condition, string $value) : void {
        echo 'wait_until' . "\n";
    }


}