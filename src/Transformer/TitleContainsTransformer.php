<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
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
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {   
        $errorMessage = 'Title does not contain'.$this->step['titleContains'];
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::titleContains($this->step['titleContains']), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Wait until title contains %s', $this->step['titleContains']);
    }

}