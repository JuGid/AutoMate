<?php 

namespace Automate\Scenario\Transformer;

use Automate\Configuration\Configuration;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverExpectedCondition;

class TextMatchesTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['textMatches'=> 
                    [
                        'regexp'=>':string',
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
        $keyLocator = array_keys($this->step['textMatches'])[1];
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::elementTextMatches(
                        WebLocator::get($keyLocator, array_values($this->step['textMatches'])[$keyLocator]),
                        $this->step['regexp']
                    ));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf("Checking if text of element %s[%s] matches %s",
                            array_keys($this->step['textMatches'])[1],
                            array_values($this->step['textMatches'])[1],
                            $this->step['textMatches']['regexp']
                        );
    }

}