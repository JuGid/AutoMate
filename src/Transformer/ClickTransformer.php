<?php

namespace Automate\Transformer;

use Automate\Handler\WindowHandler;
use Automate\Transformer\Helpers\WebLocator;

class ClickTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return [
            'click' => [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string'
                ]
            ];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        WindowHandler::setWindows($this->driver->getWindowHandles());

        $webLocator = WebLocator::get(array_keys($this->step['click'])[0], array_values($this->step['click'])[0]);
        $this->driver->findElement($webLocator)->click();
        
        $this->switchToNewWindow();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Click on element %s[%s]',
            array_keys($this->step['click'])[0],
            array_values($this->step['click'])[0]
        );
    }
}
