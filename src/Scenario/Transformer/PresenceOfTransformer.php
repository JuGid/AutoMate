<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class PresenceOfTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['presenceOf'=> 
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
        $key = array_keys($this->step['presenceOf'])[0];
        $this->driver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated(
            WebLocator::get($key , $this->step['presenceOf'][$key])
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Checking presence of element located by %s[%s]',
                            array_keys($this->step['presenceOf'])[0],
                            array_values($this->step['presenceOf'])[0]
                        );
    }

}