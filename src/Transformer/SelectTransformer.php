<?php

namespace Automate\Transformer;

use Automate\Exception\CommandException;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverRadios;
use Facebook\WebDriver\WebDriverSelect;

class SelectTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['select'=>
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
        $byElement = array_keys($this->step['select'])[0];
        $byElementValue = array_values($this->step['select'])[0];

        $element = $this->driver->findElement(WebLocator::get($byElement, $byElementValue));
        $typedElement = null;
        
        switch ($this->step['select']['type']) {
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
        
        $value = $this->step['select']['value'];
        switch ($this->step['select']['by']) {
            case 'index':
                if (!is_numeric($value)) {
                    throw new CommandException('Select by index should use an index represented by an integer');
                }
                $typedElement->selectByIndex(intval($value));
                break;
            case 'value':
                $typedElement->selectByValue($value);
                break;
            case 'text':
                $typedElement->selectByVisibleText($value);
                break;
            case 'pltext':
                $typedElement->selectByVisiblePartialText($value);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Select in %s %s[%s] by %s with value %s',
            $this->step['select']['type'],
            array_keys($this->step['select'])[0],
            array_values($this->step['select'])[0],
            $this->step['select']['by'],
            $this->step['select']['value']
        );
    }
}
