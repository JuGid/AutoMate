<?php

namespace Automate\Transformer;

use Automate\Exception\CommandException;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverRadios;
use Facebook\WebDriver\WebDriverSelect;

class DeselectTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['deselect'=>
            [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext", "pageElement")'=>':string',
                'type'=>':string :in("checkbox", "radio", "select")',
                'by'=>':string :in("value","index","text","pltext")',
                'value'=>':string or (:number :int)'
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
        $byElement = array_keys($this->step['deselect'])[0];
        $byElementValue = array_values($this->step['deselect'])[0];

        $element = $this->driver->findElement(WebLocator::get($byElement, $byElementValue));
        $typedElement = null;
        
        switch ($this->step['deselect']['type']) {
            case "checkbox":
                $typedElement = new WebDriverCheckboxes($element);
                break;
            case "radio":
                $typedElement = new WebDriverRadios($element);
                break;
            case "select":
                $typedElement = new WebDriverSelect($element);
                break;
        }
        
        $value = $this->step['deselect']['value'];

        if ($value == 'all') {
            $typedElement->deselectAll();
            return;
        }

        switch ($this->step['deselect']['by']) {
            case 'index':
                if (!is_numeric($value)) {
                    throw new CommandException('Deselect by index should use an index represented by an integer');
                }
                $typedElement->deselectByIndex(intval($value));
                break;
            case 'value':
                $typedElement->deselectByValue($value);
                break;
            case 'text':
                $typedElement->deselectByVisibleText($value);
                break;
            case 'pltext':
                $typedElement->deselectByVisiblePartialText($value);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Deselect in %s %s[%s] by %s with value %s',
            $this->step['deselect']['type'],
            array_keys($this->step['deselect'])[0],
            array_values($this->step['deselect'])[0],
            $this->step['deselect']['by'],
            $this->step['deselect']['value']
        );
    }
}
