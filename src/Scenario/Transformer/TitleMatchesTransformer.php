<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class TitleMatchesTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['titleMatches' => ':string'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $this->driver->wait()->until(WebDriverExpectedCondition::titleMatches($this->step['titleMatches']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return "Wait until title matches " . $this->step['titleMatches'];
    }

}