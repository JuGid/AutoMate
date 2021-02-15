<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class IsSelectedTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['isSelected'=> 
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
        $key = array_keys($this->step['isSelected'])[0];
        $this->driver->wait()->until(WebDriverExpectedCondition::elementToBeSelected(
            WebLocator::get($key , $this->step['isSelected'][$key])
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Checking if element located by %s[%s] is selected',
                            array_keys($this->step['isSelected'])[0],
                            array_values($this->step['isSelected'])[0]
                        );
    }

}