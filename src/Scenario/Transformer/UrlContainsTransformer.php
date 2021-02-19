<?php 

namespace Automate\Scenario\Transformer;

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
        $this->driver->wait()->until(WebDriverExpectedCondition::urlContains($this->step['urlContains']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Wait until url contains %s', $this->step['urlContains']);
    }

}