<?php

namespace Automate\Transformer;

use Automate\Console\Console;

class PrintTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['print'=>':string'];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        Console::writeln(sprintf('Print : %s', $this->step['print']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Text printed');
    }
}
