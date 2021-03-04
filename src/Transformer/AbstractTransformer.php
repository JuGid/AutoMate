<?php 

namespace Automate\Transformer;

use Automate\AutoMateEvents;
use Automate\AutoMateListener;
use Automate\Console\Console;
use Automate\Exception\DriverException;
use Automate\Exception\CommandException;
use Automate\Exception\EventException;
use Automate\Registry\VariableRegistry;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PASVL\Validation\ValidatorBuilder;

abstract class AbstractTransformer implements AutoMateListener {

    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * @var array
     */
    protected $step;

    /**
     * @codeCoverageIgnore
     */
    public function notify(string $event, $data) : int {
        if($event != AutoMateEvents::STEP_TRANSFORM) {
            return AutoMateListener::STATE_REJECTED;
        }

        if(!isset($data['step']) && !isset($data['driver'])) {
            throw new EventException('For event '. AutoMateEvents::STEP_TRANSFORM . ' you should set step and driver data');
        }

        if(array_keys($data['step'])[0] !== array_keys($this->getPattern())[0]) {
            return AutoMateListener::STATE_REJECTED;
        }

        $this->process($data['driver'], $data['step']);

        return AutoMateListener::STATE_RECEIVED;
    }

    /**
     * @codeCoverageIgnore
     * 
     * Get variables, validate and transform into driver action
     * @throws CommandException If the step is not valid
     * 
     * @param array $step
     */
    public function process(RemoteWebDriver $driver, array $step) {
        
        if($driver == null) {
            throw new DriverException('No driver provided');
        }

        $this->driver = $driver;
        $this->step = $step;
        $this->setVariables();

        if($this->validate()) {
            $this->transform();
        } else {
            throw new CommandException('The command ' . array_keys($this->step)[0] . ' is not valid');
        }

        Console::writeln((string) $this);
    }

    /**
     * Replace elements like {{ spec|scenario|global.name }} into real variable
     */
    public function setVariables() : void {
        array_walk_recursive($this->step, function(&$item, $key) {
                preg_match_all("/{{([^{]*)}}/", $item, $matches);
                $variables = $matches[1];
                array_walk($variables, function(&$variable) {
                    $variableExploded = explode('.', trim($variable));
                    $variable = VariableRegistry::get($variableExploded[0], $variableExploded[1]);
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

    /**
     * Validate array with pattern defined in class
     * @return boolean If the pattern is valide or not
     */
    public function validate() {
        $pattern = $this->getPattern();
        $builder = ValidatorBuilder::forArray($pattern);
        $validator = $builder->build();
        $value = true;

        $validator->validate($this->step);

        return $value;
    }

    /**
     * By default, print the uppercased command name
     * 
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return strtoupper(array_keys($this->step)[0]);
    }

    public function setStep(array $step) {
        $this->step = $step;
    }

    public function getStep() : array {
        return $this->step;
    }

    /**
     * Get pattern validation for lezhnev74/pasvl library
     * 
     * @link https://github.com/lezhnev74/pasvl
     * @return array
     */
    abstract protected function getPattern() : array;

    /**
     * Transform $this->step into $this->driver actions.
     * 
     */
    abstract protected function transform() : void ;

}