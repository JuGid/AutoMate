<?php 

namespace Automate\Transformer;

use Automate\Transformer\Helpers\WebLocator;

class MouseTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['mouse'=>[
            ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string',
            'event'=> ':string :in("click","doubleClick","mouseDown","mouseUp","mouseMove")'
        ]];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $element = $this->driver->findElement(WebLocator::get(
            array_keys($this->step['mouse'])[0],
            array_values($this->step['mouse'])[0]
        ));

        $this->driver->getMouse()->{$this->step['mouse']['event']}($element->getCoordinates());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s use on %s[%s]', 
            ucfirst($this->step['mouse']['event']),
            array_keys($this->step['mouse'])[0],
            array_values($this->step['mouse'])[0]
        );
    }
}