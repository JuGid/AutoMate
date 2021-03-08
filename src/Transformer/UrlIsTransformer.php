<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Facebook\WebDriver\WebDriverExpectedCondition;

class UrlIsTransformer extends AbstractTransformer
{

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
        $errorMessage = 'Url is not '.$this->step['urlIs'];
        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                    ->until(WebDriverExpectedCondition::urlIs($this->step['urlIs']), $errorMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Wait until url is %s', $this->step['urlIs']);
    }
}
