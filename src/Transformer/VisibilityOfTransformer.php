<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class VisibilityOfTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['visibilityOf'=>
                    [
                        ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string'
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
        $key = array_keys($this->step['visibilityOf'])[0];
        $errorMessage = sprintf('%s[%s] is not visible', $key, $this->step['visibilityOfAny'][$key]);
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::visibilityOfElementLocated(
                         WebLocator::get($key, $this->step['visibilityOf'][$key])
                     ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Checking visibility of element located by %s[%s]',
            array_keys($this->step['visibilityOf'])[0],
            array_values($this->step['visibilityOf'])[0]
        );
    }
}
