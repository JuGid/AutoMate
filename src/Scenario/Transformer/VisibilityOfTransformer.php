<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class VisibilityOfTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['visibilityOf'=> 
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
        $key = array_keys($this->step['visibilityOf'])[0];
        $this->driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(
            WebLocator::get($key , $this->step['visibilityOf'][$key])
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Checking visibility of element located by %s[%s]',
                            array_keys($this->step['visibilityOf'])[0],
                            array_values($this->step['visibilityOf'])[0]
                        );
    }

}