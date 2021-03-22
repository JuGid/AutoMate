<?php

namespace Automate\Transformer;

use Automate\Transformer\Helpers\WebLocator;

class SubmitTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['submit'=>[
            ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string',
            'text'=>':string'
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $webLocator = WebLocator::get(array_keys($this->step['submit'])[0], array_values($this->step['submit'])[0]);
        $this->driver->findElement($webLocator)
                     ->sendKeys($this->step['submit']['text'])
                     ->submit();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Submit %s for element %s[%s]',
            $this->step['submit']['text'],
            array_keys($this->step['submit'])[0],
            array_values($this->step['submit'])[0]
        );
    }
}
