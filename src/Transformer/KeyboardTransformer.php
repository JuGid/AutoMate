<?php

namespace Automate\Transformer;

class KeyboardTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['keyboard'=>[
            'event'=> ':string :in("releaseKey","pressKey", "sendKeys")',
            'keys'=> [':string *' => ':string']
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $keyboard = $this->driver->getKeyboard();
        
        $event = $this->step['keyboard']['event'];

        foreach ($this->step['keyboard']['keys'] as $k=>$keyToSend) {
            $keyboard->{$event}($keyToSend);
            //$keyboard->$event($keyToSend); this is not a pretty thing to do
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Keyboard use on %s', $this->step['keyboard']['event']);
    }
}
