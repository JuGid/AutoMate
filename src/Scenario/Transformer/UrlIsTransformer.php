<?php 

namespace Automate\Scenario\Transformer;

use Automate\Driver\DriverManager;
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
     * 
     * @codeCoverageIgnore
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
        return sprintf('Wait until url is %s', $this->step['urlIs']);
    }

}