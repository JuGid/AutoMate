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
        return "Wait until contains " . $this->step['urlContains'];
    }

}