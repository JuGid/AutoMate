<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
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
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {   
        $errorMessage = 'Title does not match '.$this->step['titleMatches'];
        $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                     ->until(WebDriverExpectedCondition::titleMatches($this->step['titleMatches']), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Wait until title matches regexp %s', $this->step['titleMatches']);
    }

}