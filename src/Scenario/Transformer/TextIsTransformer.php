<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class TextIsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['textIs'=> 
                    [
                        'value'=>':string',
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
        $keyLocator = array_keys($this->step['textIs'])[1];
        $this->driver->wait()->until(WebDriverExpectedCondition::elementTextIs(
            WebLocator::get($keyLocator, array_values($this->step['textIs'])[$keyLocator]),
            $this->step['value']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf("Checking if text of element %s[%s] is %s",
                            array_keys($this->step['textIs'])[1],
                            array_values($this->step['textIs'])[1],
                            $this->step['textIs']['value']
                        );
    }

}