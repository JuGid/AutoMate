<?php

namespace Automate\Transformer;

use Facebook\WebDriver\WebDriverKeys;

class KeyboardTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['keyboard'=>[
            'event'=> ':string :in("releaseKey","pressKey", "sendKeys")',
            'keys'=> ':any'
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $event = $this->step['keyboard']['event'];
        $this->driver->getKeyboard()->{$event}($this->step['keyboard']['keys']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Keyboard use on %s', $this->step['keyboard']['event']);
    }
}
