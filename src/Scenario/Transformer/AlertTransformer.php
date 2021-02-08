<?php 

namespace Automate\Scenario\Transformer;

use Facebook\WebDriver\WebDriverExpectedCondition;

class AlertTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     * @todo sendkeys and gettext to implement
     */
    protected function getPattern()
    {
        return ['alert' => ':string :regexp("(accept)|(dismiss)|(isPresent)")'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
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

}