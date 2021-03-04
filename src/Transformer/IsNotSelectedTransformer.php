<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class IsNotSelectedTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['isNotSelected'=> 
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
        $key = array_keys($this->step['isNotSelected'])[0];
        $errorMessage = sprintf('%s[%s] is selected', $key, $this->step['isNotSelected'][$key]);
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::elementSelectionStateToBe(
                        WebLocator::get($key , $this->step['isNotSelected'][$key]), false
                    ), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Checking if element located by %s[%s] is not selected',
                            array_keys($this->step['isNotSelected'])[0],
                            array_values($this->step['isNotSelected'])[0]
                        );
    }

}