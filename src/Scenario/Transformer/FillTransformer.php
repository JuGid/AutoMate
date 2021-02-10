<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class FillTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return [
            'fill' => [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string', 
                'with' => ':string'
                ]
            ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {
        $webLocator = WebLocator::get(array_keys($this->step['fill'])[0], array_values($this->step['fill'])[0]);
        $this->driver->findElement($webLocator)->sendKeys($this->step['fill']['with']);
    }

}