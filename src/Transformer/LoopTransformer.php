<?php

namespace Automate\Transformer;

use Automate\AutoMateEvents;
use Automate\Console\Console;
use Automate\Exception\NotImplementedException;

class LoopTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['loop'=> [
                'repeat'=> ':number',
                'steps'=> ':array'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $runFor = intval($this->step['loop']['repeat']);

        Console::writeln(sprintf("> Loop begins for %d times", $runFor));

        for ($i = 0; $i<$runFor; $i++) {
            foreach ($this->step['loop']['steps'] as $step) {
                $this->dispatcher->notify(AutoMateEvents::STEP_TRANSFORM, [
                    'driver'=> $this->driver,
                    'step'=>$step,
                    'logger'=>$this->logger
                ]);
            }
            Console::separator('.', 3);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('> Loop ends after %s times', $this->step['loop']['repeat']);
    }
}
