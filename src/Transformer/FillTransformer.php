<?php

namespace Automate\Transformer;

use Automate\Transformer\Helpers\WebLocator;

class FillTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return [
            'fill' => [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string',
                'with' => ':string',
                'before?' => ':string :in("clear")'
                ]
            ];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $webLocator = WebLocator::get(array_keys($this->step['fill'])[0], array_values($this->step['fill'])[0]);
        $element = $this->driver->findElement($webLocator);

        if (isset($this->step['fill']['before'])) {
            $element->clear();
        }

        $element->sendKeys($this->step['fill']['with']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Fill element %s[%s] with value %s',
            array_keys($this->step['fill'])[0],
            array_values($this->step['fill'])[0],
            $this->step['fill']['with']
        );
    }
}
