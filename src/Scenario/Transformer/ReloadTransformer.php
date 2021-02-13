<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

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
        return 'Reload page';
    }

}