<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\NotAValidCommand;
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
                ':string :regexp("(css)|(xpath)|(id)|(class)|(name)|(tag)|(linktext)|(pltext)")'=>':string',
                'by'=>':string :regexp("(value)|(index)|(text)|(pltext)")',
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
                throw new NotAValidCommand('select');
            }
        }

    }

}