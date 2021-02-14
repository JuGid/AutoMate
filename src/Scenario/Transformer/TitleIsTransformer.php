<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class TitleIsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['titleIs' => ':string'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $this->driver->wait()->until(WebDriverExpectedCondition::titleIs($this->step['titleIs']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return "Wait until title is " . $this->step['titleIs'];
    }

}