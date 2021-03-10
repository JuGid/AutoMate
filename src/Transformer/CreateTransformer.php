<?php

namespace Automate\Transformer;

use Automate\Handler\WindowHandler;
use Facebook\WebDriver\WebDriverTargetLocator;

class CreateTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['create' => ':string :in("tab","window")'];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        WindowHandler::addPreviousWindow($this->driver->getWindowHandle());

        $array = $this->step['create'];
        if ($array == 'tab') {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_TAB);
        } elseif ($array == 'window') {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_WINDOW);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Create a new %s and switch to it', $this->step['create']);
    }
}
