<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * @codeCoverageIgnore
 */
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
     */
    protected function transform() : void
    {   
        $array = $this->step['alert'];
        if($array == 'accept') {
            $this->driver->switchTo()->alert()->accept();
        } elseif($array == 'dismiss') {
            $this->driver->switchTo()->alert()->dismiss();
        } elseif($array == 'isPresent') {
            $this->driver->wait()->until(WebDriverExpectedCondition::alertIsPresent());
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