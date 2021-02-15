<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
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
     */
    protected function transform() : void
    {   
        $key = array_keys($this->step['isNotSelected'])[0];
        $this->driver->wait()->until(WebDriverExpectedCondition::elementSelectionStateToBe(
            WebLocator::get($key , $this->step['isNotSelected'][$key]), false
        ));
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