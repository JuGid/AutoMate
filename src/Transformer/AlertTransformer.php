<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Automate\Exception\CommandException;
use Facebook\WebDriver\WebDriverExpectedCondition;

class AlertTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['alert' => [
                    'type'=>':string :in("accept", "dismiss", "isPresent", "sendKeys")',
                    'value?'=>':string'
                ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        switch($this->step['alert']['type']){
            case 'accept':
                $this->driver->switchTo()->alert()->accept();
                break;
            case 'dismiss':
                $this->driver->switchTo()->alert()->dismiss();
                break;
            case 'isPresent':
                $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                             ->until(WebDriverExpectedCondition::alertIsPresent(), 'Alert is not present');
                break;
            case 'sendKeys':
                if(!isset($this->step['alert']['value'])) {
                    throw new CommandException('Should define a value when using sendKeys');
                }
                $this->driver->switchTo()->alert()->sendKeys($this->step['alert']['value']);
                break;
        } 
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('On alert : %s', $this->step['alert']['type']);
    }
}
