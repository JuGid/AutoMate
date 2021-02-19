<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * @codeCoverageIgnore
 */
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
     */
    protected function transform() : void
    {   
        $key = array_keys($this->step['visibilityOfAny'])[0];
        $this->driver->wait()->until(WebDriverExpectedCondition::visibilityOfAnyElementLocated(
            WebLocator::get($key , $this->step['visibilityOfAny'][$key])
        ));
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