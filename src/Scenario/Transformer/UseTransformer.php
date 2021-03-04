<?php 

namespace Automate\Scenario\Transformer;

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

        $this->step['use']['scenario_file'] = sprintf('%s/%s.yaml', $name, $sub);

        $scenario = new Scenario($name, $sub, false);
        $sttr = new StepTransformer($this->driver);
        
        foreach($scenario as $step){
            $sttr->transform($step);
        }

    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return sprintf('Use scenario %s', $this->step['use']['scenario_file']);
    }
}