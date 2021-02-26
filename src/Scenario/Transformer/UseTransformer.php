<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\ScenarioException;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use Automate\Scenario\Scenario;
use Automate\Scenario\StepTransformer;

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
        
        if($askedScenario[0] == 'main' && $askedScenario[1] == VariableRegistry::get(Scope::WORLD, 'scenario')) {
            throw new ScenarioException('A loop has been detected, please try not to use the same scenario');
        }

        $scenario = new Scenario($askedScenario[1], $askedScenario[0]);
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