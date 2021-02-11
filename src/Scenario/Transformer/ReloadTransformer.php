<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class ReloadTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return ['reload'=>'page'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
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