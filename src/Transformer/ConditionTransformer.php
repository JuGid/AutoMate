<?php 

namespace Automate\Transformer;

use Automate\Exception\NotImplementedException;

class ConditionTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['condition'=>
                    [
                        'eval'=>':string',
                        'ifTrue'=>[
                            'steps'=>':array'
                        ],
                        'ifFalse'=>[
                            'steps'=>':array'
                        ]
                    ]
                ];
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
        /*
        $result = eval(sprintf('return %s;',$this->step['condition']['eval']));
        $this->step['condition']['result'] = $result ? 'true':'false';

        $steps = $result ? $this->step['condition']['ifTrue']['steps'] : $this->step['condition']['ifFalse']['steps'];
        $sttr = new StepTransformer($this->driver);
        
        foreach($steps as $step) {
            $sttr->transform($step);
        }
        */
        throw new NotImplementedException('Condition is not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Condition assessed %s as %s', 
                    $this->step['condition']['eval'],
                    $this->step['condition']['result']
                );
    }
}