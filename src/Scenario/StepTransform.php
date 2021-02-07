<?php 

namespace Automate\Scenario;

use Facebook\WebDriver\WebDriverBy;

class StepTransform {

    private $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function transform(array $step) : void{
        $transformerClass = __NAMESPACE__ . '\\Transformer\\' . ucfirst(key($step)) . 'Transformer';
        $transformer = new $transformerClass();
        $transformer->process($this->driver, $step);        
    }

    //public function go(string $adresse) : void {
    //    $this->driver->get($adresse);
    //}
}