<?php

namespace Automate\Transformer;

use Automate\Handler\WindowHandler;

class CloseTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['close'=>'window'];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $this->driver->close();
        $this->driver->switchTo()->window(WindowHandler::getPreviousWindow());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Close window');
    }
}
