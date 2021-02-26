<?php 

namespace Automate\Scenario\Transformer;

use Automate\Configuration\Configuration;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class IsClickableTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['isClickable'=> 
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
        $key = array_keys($this->step['isClickable'])[0];
        $errorMessage = sprintf('%s[%s] is not clickable', $key, $this->step['isClickable'][$key]);
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::elementToBeClickable(
                        WebLocator::get($key, $this->step['isClickable'][$key])
                    ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Checking if %s[%s] is clickable',
                            array_keys($this->step['isClickable'])[0],
                            array_values($this->step['isClickable'])[0]
                        );
    }

}