<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class NumberOfWindowsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['numberOfWindows' => ':number :int'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $this->driver->wait()->until(WebDriverExpectedCondition::numberOfWindowsToBe(intval($this->step['numberOfWindows'])));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'Wait until number of windows is ' . intval($this->step['numberOfWindows']);
    }

}