<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;
use Facebook\WebDriver\WebDriverExpectedCondition;

class AlertTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['alert' => ':string :in("accept", "dismiss", "isPresent")'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {   
        $array = $this->step['alert'];
        if($array == 'accept') {
            $this->driver->switchTo()->alert()->accept();
        } elseif($array == 'dismiss') {
            $this->driver->switchTo()->alert()->dismiss();
        } elseif($array == 'isPresent') {
            $this->driver->wait(Configuration::get('wait.for'),Configuration::get('wait.every'))
                         ->until(WebDriverExpectedCondition::alertIsPresent(), 'Alert is not present');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('On alert : %s', $this->step['alert']);
    }

}