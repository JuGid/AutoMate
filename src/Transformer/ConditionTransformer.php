<?php

namespace Automate\Transformer;

use Automate\AutoMateEvents;
use Automate\Exception\NotImplementedException;
use Automate\Transformer\Logic\LogicExecutor;

class ConditionTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['condition'=> [
                        ':string :in("eval","logic")'=>':string',
                        'correct'=>['steps'=>':array'],
                        'incorrect'=>['steps'=>':array']
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
        switch (array_keys($this->getStepData())[0]) {
            case 'eval':
                $result = eval(sprintf('return %s;', $this->getStepData()['eval']));
                break;
            case 'logic':
                $logicExecutor = new LogicExecutor();
                $logicExecutor->setEventDispatcher($this->dispatcher);
                $result = $logicExecutor->for($this->getStepData()['logic'])
                                        ->execute()
                                        ->getAnswser();
                break;
            default:
                $result = false;
                break;
        }

        $this->step['condition']['result'] = $result ? 'true':'false';

        $steps = $result ? $this->getStepData()['correct']['steps'] : $this->getStepData()['incorrect']['steps'];
        
        foreach ($steps as $step) {
            $this->dispatcher->notify(AutoMateEvents::STEP_TRANSFORM, [
                'driver'=> $this->driver,
                'step'=>$step,
                'logger'=>$this->logger
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
            $this->getStepData()['eval'],
            $this->getStepData()['result']
        );
    }
}
