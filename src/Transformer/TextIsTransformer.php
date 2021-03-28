<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class TextIsTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['textIs'=>
                    [
                        ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string',
                        'value'=>':string'
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
        $keyLocator = array_keys($this->step['textIs'])[0];
        $errorMessage = sprintf('%s[%s] text is not %s', $keyLocator, $this->step['textIs'][$keyLocator], $this->step['textIs']['value']);
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::elementTextIs(
                         WebLocator::get($keyLocator, $this->step['textIs'][$keyLocator]),
                         $this->step['textIs']['value']
                     ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            "Checking if text of element %s[%s] is %s",
            array_keys($this->step['textIs'])[1],
            array_values($this->step['textIs'])[1],
            $this->step['textIs']['value']
        );
    }
}
