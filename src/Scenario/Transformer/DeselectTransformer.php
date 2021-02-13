<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\CommandException;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverSelect;

class DeselectTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['deselect'=> 
            [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string',
                'by'=>':string :in("value","index","text","pltext")',
                'value'=>':string and (:number :int)'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {
        $element = $this->driver->findElement(WebLocator::get(array_keys($this->step['deselect'])[0], array_values($this->step['deselect'])[0]));
        $select = new WebDriverSelect($element);
        $deselectArray = $this->step['deselect'];

        if($deselectArray['by'] == 'index' && is_numeric($deselectArray['value'])) {
            $select->deselectByIndex($deselectArray['value']);
        } else {
            if(is_string($deselectArray['value'])) {
                switch($deselectArray['by']) {
                    case 'value':
                        if($deselectArray['value'] == 'all') {
                            $select->deselectAll();
                        } else {
                            $select->selectByValue($deselectArray['value']);
                        }
                        break;
                    case 'text':
                        $select->deselectByVisibleText($deselectArray['value']);
                        break;
                    case 'pltext':
                        $select->deselectByVisiblePartialText($deselectArray['value']);
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
        $str = 'Deselect element with ['.array_keys($this->step['deselect'])[0].'] => [';
        $str.= array_values($this->step['deselect'])[0].'] by:['.$this->step['deselect']['by'];
        $str.= '] with value:['.$this->step['deselect']['value'].']';

        return $str;
    }

}