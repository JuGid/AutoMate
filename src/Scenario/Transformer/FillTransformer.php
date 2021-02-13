<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class FillTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
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
    protected function transform() : void
    {
        $webLocator = WebLocator::get(array_keys($this->step['fill'])[0], array_values($this->step['fill'])[0]);
        $this->driver->findElement($webLocator)->sendKeys($this->step['fill']['with']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $str = 'Fill element with [' . array_keys($this->step['fill'])[0] .'] => [';
        $str.= array_values($this->step['fill'])[0] . '] with value ' . $this->step['fill']['with'];
        
        return $str;
    }

}