<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\ScenarioExitException;

class ExitTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['exit'=>':string'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        throw new ScenarioExitException($this->step['exit']);
    }

}