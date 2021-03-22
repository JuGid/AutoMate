<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class PresenceOfTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['presenceOf'=>
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
        $key = array_keys($this->step['presenceOf'])[0];
        $errorMessage = sprintf('%s[%s] is not present', $key, $this->step['presenceOf'][$key]);
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::presenceOfElementLocated(
                         WebLocator::get($key, $this->step['presenceOf'][$key])
                     ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Checking presence of element located by %s[%s]',
            array_keys($this->step['presenceOf'])[0],
            array_values($this->step['presenceOf'])[0]
        );
    }
}
