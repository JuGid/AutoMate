<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class TextContainsTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['textContains'=>
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
        $keyLocator = array_keys($this->step['textContains'])[0];
        $errorMessage = sprintf('%s[%s] text does not contains %s', $keyLocator, $this->step['textContains'][$keyLocator], $this->step['textContains']['value']);
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::elementTextContains(
                         WebLocator::get($keyLocator, $this->step['textContains'][$keyLocator]),
                         $this->step['textContains']['value']
                     ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            "Checking if text of element %s[%s] contains %s",
            array_keys($this->step['textContains'])[1],
            array_values($this->step['textContains'])[1],
            $this->step['textContains']['value']
        );
    }
}
