<?php

namespace Automate\Transformer;

use Automate\Transformer\Helpers\WebLocator;

class FormTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['form'=>[
            ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string',
            'type'=>':string :in("submit")'
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $formElement = $this->driver->findElement(WebLocator::get(
            array_keys($this->step['form'])[0],
            array_values($this->step['form'])[0]
        ));

        switch ($this->step['form']['type']) {
            case 'submit':
                $formElement->submit();
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Form %s[%s] %s',
            array_keys($this->step['form'])[0],
            array_values($this->step['form'])[0],
            $this->step['form']['type']
        );
    }
}
