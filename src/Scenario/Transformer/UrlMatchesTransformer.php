<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class UrlMatchesTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['urlMatches' => ':string'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $this->driver->wait()->until(WebDriverExpectedCondition::urlMatches($this->step['urlMatches']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return "Wait until url matches " . $this->step['urlMatches'];
    }

}