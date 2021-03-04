<?php 

namespace Automate\Transformer;

use Automate\Console\Console;
use Automate\Exception\NotImplementedException;

class LoopTransformer extends AbstractTransformer {

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
        /*
        $runFor = intval($this->step['loop']['repeat']);
        $sttr = new StepTransformer($this->driver);

        Console::writeln(sprintf("> Loop begins for %d times", $runFor));

        for($i = 0; $i<$runFor; $i++) {
            foreach($this->step['loop']['steps'] as $step) {
                $sttr->transform($step);
            }
            Console::separator('.', 3);
        }
        */
        throw new NotImplementedException('Loop is not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('> Loop ends after %s times', $this->step['loop']['repeat']);
    }
}