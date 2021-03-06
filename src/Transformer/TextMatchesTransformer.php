<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class TextMatchesTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['textMatches'=>
                    [
                        ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string',
                        'regexp'=>':string'
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
        $keyLocator = array_keys($this->step['textMatches'])[0];
        $errorMessage = sprintf('%s[%s] text does not match %s', $keyLocator, $this->step['textMatches'][$keyLocator], $this->step['textMatches']['regexp']);
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::elementTextMatches(
                         WebLocator::get($keyLocator, $this->step['textMatches'][$keyLocator]),
                         $this->step['textMatches']['regexp']
                     ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            "Checking if text of element %s[%s] matches %s",
            array_keys($this->step['textMatches'])[1],
            array_values($this->step['textMatches'])[1],
            $this->step['textMatches']['regexp']
        );
    }
}
