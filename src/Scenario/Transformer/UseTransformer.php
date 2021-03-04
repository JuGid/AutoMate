<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\ScenarioException;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use Automate\Scenario\Scenario;
use Automate\Scenario\StepTransformer;
use InvalidArgumentException;

class UseTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['use'=>':string'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $askedScenario = explode('.', $this->step['use']);

        if($askedScenario[0] == 'main') {
            $name = $askedScenario[1];
            $sub = 'main';
        } elseif($askedScenario[0] == 'sub') {
            $name = VariableRegistry::get(Scope::WORLD, 'scenario');
            $sub = $askedScenario[1];
        } else {
            throw new InvalidArgumentException('You should use main or sub to import a scenario');
        }

        $scenario = new Scenario($name, $sub, false);
        $sttr = new StepTransformer($this->driver);
        
        foreach($scenario as $step){
            $sttr->transform($step);
        }

        $this->step['use']['type'] = $askedScenario[0];
        $this->step['use']['name'] = $askedScenario[1];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return sprintf('Use scenario %s %s', $this->step['use']['type'], $this->step['use']['name']);
    }
}