<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\NotAValidCommandException;
use Automate\Handler\VariableHandlerHandler;
use PASVL\Validation\ValidatorBuilder;
use PASVL\Validation\Problems\ArrayFailedValidation;

abstract class AbstractTransformer {

    protected $driver;
    protected $step;

    public function process($driver, array $step) {
        $this->driver = $driver;
        $this->step = $step;
        $this->setVariables();

        if($this->validate()) {
            $this->transform();
        } else {
            throw new NotAValidCommandException(array_keys($this->step)[0]);
        }
    }

    /**
     * @todo implement spec and globale variable to replace
     */
    private function setVariables() {
        array_walk_recursive($this->step, function(&$item, $key) {
                preg_match_all("/{{([^{]*)}}/", $item, $matches);
                $variables = $matches[1];
                array_walk($variables, function(&$variable) {
                    $variableExploded = explode('.', trim($variable));
                    $variable = VariableHandlerHandler::get($variableExploded[0], $variableExploded[1]);
                });

                $index = 0;
                $item = preg_replace_callback("/{{([^{]*)}}/", function($matches) use($variables, &$index){
                    $str = str_replace($matches[$index], $variables[$index], $matches[$index]);
                    $index++;
                    return $str;
                    
                }, $item);                
            }
        );
    }

    public function validate() {
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

    /**
     * Get pattern validation for lezhnev74/pasvl library
     * @link https://github.com/lezhnev74/pasvl
     */
    abstract protected function getPattern();

    /**
     * Transform $this->step into $this->driver actions.
     */
    abstract protected function transform();

}