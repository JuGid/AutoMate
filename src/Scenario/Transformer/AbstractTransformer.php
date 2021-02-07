<?php 

namespace Automate\Scenario\Transformer;

use PASVL\Validation\ValidatorBuilder;
use PASVL\Validation\Problems\ArrayFailedValidation;

abstract class AbstractTransformer {

    protected $driver;
    protected $step;

    public function process($driver, array $step) {
        $this->driver = $driver;
        $this->step = $step;

        if($this->validate()) {
            $this->transform();
        } else {
            //poor design to change
            die();
        }
    }

    private function validate() {
        $pattern = $this->getPattern();
        $builder = ValidatorBuilder::forArray($pattern);
        $validator = $builder->build();
        $value = true;

        try {
            $validator->validate($this->step);
        } catch (ArrayFailedValidation $e) {
            echo "Step [". key($this->step)  ."] is not a valid step. Watch out the syntax pattern.\n";
            echo "[Error details] " . $e->getMessage() . "\n";
            $value = false;
        }

        return $value;
    }

    abstract protected function getPattern();
    abstract protected function transform();

}