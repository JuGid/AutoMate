<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\NotAValidCommandException;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverSelect;

class DeselectTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return ['select'=> 
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
    protected function transform() 
    {
        $element = $this->driver->findElement(WebLocator::get(array_keys($this->step['select'])[0], array_values($this->step['select'])[0]));
        $select = new WebDriverSelect($element);
        $deselectArray = $this->step['select'];

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
                throw new NotAValidCommandException('deselect');
            }
        }

    }

}