<?php

namespace Automate\Transformer;

use Automate\AutoMateEvents;
use Automate\Exception\NotImplementedException;

class ConditionTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['condition'=> [
                        'eval'=>':string',
                        'ifTrue'=>['steps'=>':array'],
                        'ifFalse'=>['steps'=>':array']
                    ]];
    }

    /**
     * {@inheritdoc}
     *
     * If someone can PR something better as a solution
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $result = eval(sprintf('return %s;', $this->step['condition']['eval']));
        $this->step['condition']['result'] = $result ? 'true':'false';

        $steps = $result ? $this->step['condition']['ifTrue']['steps'] : $this->step['condition']['ifFalse']['steps'];
        
        foreach ($steps as $step) {
            $this->dispatcher->notify(AutoMateEvents::STEP_TRANSFORM, [
                'driver'=> $this->driver,
                'step'=>$step
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Condition assessed %s as %s',
            $this->step['condition']['eval'],
            $this->step['condition']['result']
        );
    }
}
