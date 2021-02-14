<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class TitleContainsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['titleContains' => ':string'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $this->driver->wait()->until(WebDriverExpectedCondition::titleContains($this->step['titleContains']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return "Wait until title contains " . $this->step['titleContains'];
    }

}