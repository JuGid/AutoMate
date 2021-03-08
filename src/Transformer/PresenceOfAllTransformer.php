<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class PresenceOfAllTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['presenceOfAll'=>
                    [
                        ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string'
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
        $key = array_keys($this->step['presenceOfAll'])[0];
        $errorMessage = sprintf('For every %s[%s] none is present', $key, $this->step['presenceOfAll'][$key]);
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                         WebLocator::get($key, $this->step['presenceOfAll'][$key])
                     ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Checking presence of all elements located by %s[%s]',
            array_keys($this->step['presenceOfAll'])[0],
            array_values($this->step['presenceOfAll'])[0]
        );
    }
}
