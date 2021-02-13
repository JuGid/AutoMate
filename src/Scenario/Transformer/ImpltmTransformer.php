<?php 

namespace Automate\Scenario\Transformer;

class ImpltmTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['impltm'=>':number :int'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {
        $this->driver->manage()->timeouts()->implicitlyWait($this->step['impltm']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'Set implicit timeout :impltm['.$this->step['impltm'].']';
    }

}