<?php 

namespace Automate\Scenario\Transformer;

/**
 * @codeCoverageIgnore
 */
class ReloadTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['reload'=>'page'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {
        $this->driver->navigate()->refresh();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Reload the page');
    }

}