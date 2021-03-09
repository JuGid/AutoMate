<?php

namespace Automate\Transformer;

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
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Close window');
    }
}
