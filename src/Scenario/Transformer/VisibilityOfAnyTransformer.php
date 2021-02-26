<?php 

namespace Automate\Scenario\Transformer;

use Automate\Configuration\Configuration;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class VisibilityOfAnyTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['visibilityOfAny'=> 
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
        $key = array_keys($this->step['visibilityOfAny'])[0];
        $errorMessage = sprintf('For every %s[%s] none are visible', $key, $this->step['visibilityOfAny'][$key]);
        
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::visibilityOfAnyElementLocated(
                        WebLocator::get($key , $this->step['visibilityOfAny'][$key])
                    ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Checking visibility of any elements located by %s[%s]',
                            array_keys($this->step['visibilityOfAny'])[0],
                            array_values($this->step['visibilityOfAny'])[0]
                        );
    }

}