<?php 

namespace Automate\Scenario\Transformer;

use Automate\Handler\WindowHandler;

class CreateTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     * @todo sendkeys and gettext to implement
     */
    protected function getPattern()
    {
        return ['create' => ':string :in("tab","window")'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {   
        // Coming with the new php-webdriver release
        /*
        $array = $this->step['create'];
        if($array == 'tab') {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_TAB);
        } elseif($array == 'window') {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_WINDOW);
            $this->driver->switchTo()->newWindow();
        } 
        */
        $this->driver->switchTo()->newWindow();
        WindowHandler::setWindows($this->driver->getWindowHandles());
    }

}