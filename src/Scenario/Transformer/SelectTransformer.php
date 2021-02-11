<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\CommandException;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverSelect;

class SelectTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return ['select'=> 
            [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string',
                'by'=>':string in("value","index","text","pltext")',
                'value'=>':string and (:number :int)'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {
        $element = $this->driver->findElement(WebLocator::get(array_keys($this->step['select'])[0], array_values($this->step['select'])[0]));
        $select = new WebDriverSelect($element);
        $selectArray = $this->step['select'];
        if($selectArray['by'] == 'index' && is_numeric($selectArray['value'])) {
            $select->selectByIndex($selectArray['value']);
        } else {
            if(is_string($selectArray['value'])) {
                switch($selectArray['by']) {
                    case 'value':
                        $select->selectByValue($selectArray['value']);
                        break;
                    case 'text':
                        $select->selectByVisibleText($selectArray['value']);
                        break;
                    case 'pltext':
                        $select->selectByVisiblePartialText($selectArray['value']);
                        break;
                }
            }else {
                throw new CommandException('The deselect value has to be of type string/text');
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {   
        $str = 'Select element with ['.array_keys($this->step['select'])[0].'] => [';
        $str.= array_values($this->step['select'])[0].'] by:['.$this->step['select']['by'];
        $str.= '] with value:['.$this->step['select']['value'].']';
        
        return $str;
    }

}