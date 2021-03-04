<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Facebook\WebDriver\WebDriverExpectedCondition;

class UrlContainsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['urlContains' => ':string'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {   
        $errorMessage = 'Url does not contains '.$this->step['urlContains'];
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::urlContains($this->step['urlContains']), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Wait until url contains %s', $this->step['urlContains']);
    }

}