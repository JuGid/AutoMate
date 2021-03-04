<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Facebook\WebDriver\WebDriverExpectedCondition;

class NumberOfWindowsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['numberOfWindows' => ':number'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {   
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::numberOfWindowsToBe(intval($this->step['numberOfWindows'])));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Wait until number of windows is %s', intval($this->step['numberOfWindows']));
    }

}