<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\NotImplementedException;

/**
 * @codeCoverageIgnore
 */
class CreateTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array 
    {
        return ['create' => ':string :in("tab","window")'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
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
        
        //$this->driver->switchTo()->newWindow();
        //WindowHandler::setWindows($this->driver->getWindowHandles());
        throw new NotImplementedException('Command not implemented yet. Waiting php-driver new release.');
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Create a new %s and switch to it',$this->step['create']);
    }

}