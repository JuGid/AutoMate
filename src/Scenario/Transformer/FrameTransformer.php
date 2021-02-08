<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class FrameTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return ['frame'=>[
            ':string :regexp("(css)|(xpath)|(id)|(class)|(name)|(tag)|(linktext)|(pltext)") ?'=>':string',
            'index?'=>':number :int',
            'default?'=>''
        ]];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {   
        $keys = array_keys($this->step['frame']);
        $values = array_values($this->step['frame']);

        if($keys[0] == 'index') {
            $this->driver->switchTo()->frame($this->step['frame']['index']);
        } else {
            $this->driver->switchTo()->frame(WebLocator::get($keys[0], $values[0]));
        }
    }

}