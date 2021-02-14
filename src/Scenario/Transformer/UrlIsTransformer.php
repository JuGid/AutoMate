<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class UrlIsTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['urlIs' => ':string'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $this->driver->wait()->until(WebDriverExpectedCondition::urlIs($this->step['urlIs']));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return "Wait until url is " . $this->step['urlIs'];
    }

}