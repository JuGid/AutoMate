<?php

namespace Automate\Transformer;

use Automate\Transformer\Helpers\WebLocator;

class FrameTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['frame'=>[
            ':string :in("css","xpath","id","class","name","tag","linktext", "pltext") ?'=>':string',
            'index?'=>':number',
            'default?'=>'content'
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $keys = array_keys($this->step['frame']);
        $values = array_values($this->step['frame']);

        if ($keys[0] == 'index') {
            $this->driver->switchTo()->frame($this->step['frame']['index']);
        } elseif ($keys[0] == 'default') {
            $this->driver->switchTo()->defaultContent();
        } else {
            $element = $this->driver->findElement(WebLocator::get($keys[0], $values[0]));
            $this->driver->switchTo()->frame($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Frame to string');
    }
}
