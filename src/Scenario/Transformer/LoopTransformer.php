<?php 

namespace Automate\Scenario\Transformer;

use Automate\Console\Console;

class LoopTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return [
            'loop' => [
                'count'=>':number',
                'sub'=>':string'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $countInArray = $this->step['loop']['count'];
        $count = intval($countInArray);

        for($i= 0; $i < $count; $i++) {
            Console::writeln('Run ' . $i);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Running sub scenario %s for %d time',
            $this->step['loop']['sub'] ,
            $this->step['loop']['count']
        );
    }
}