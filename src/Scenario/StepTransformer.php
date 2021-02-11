<?php 

namespace Automate\Scenario;

use Automate\Exception\NotAValidCommandException;

class StepTransformer {

    private $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function transform(array $step) : void{
        $transformerClass = __NAMESPACE__ . '\\Transformer\\' . ucfirst(key($step)) . 'Transformer';
        if(class_exists($transformerClass)) {
            $transformer = new $transformerClass();
            $transformer->process($this->driver, $step);
            echo $transformer . "\n";
        } else {
            throw new NotAValidCommandException(key($step));
        }
               
    }
}