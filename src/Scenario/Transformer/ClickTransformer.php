<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class ClickTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return [
            'click' => [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string'
                ]
            ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {
        $webLocator = WebLocator::get(array_keys($this->step['click'])[0], array_values($this->step['click'])[0]);
        $this->driver->findElement($webLocator)->click();
    }

}