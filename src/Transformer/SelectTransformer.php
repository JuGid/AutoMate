<?php 

namespace Automate\Transformer;

use Automate\Exception\CommandException;
use Automate\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverSelect;

class SelectTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['select'=> 
            [
                ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string',
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
        $element = $this->driver->findElement(WebLocator::get(array_keys($this->step['select'])[0], array_values($this->step['select'])[0]));
        $select = new WebDriverSelect($element);
        $selectArray = $this->step['select'];
        if($selectArray['by'] == 'index' && is_numeric($selectArray['value'])) {
            $select->selectByIndex(intval($selectArray['value']));
        } elseif(is_string($selectArray['value'])) {
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
            throw new CommandException('The select value has to be of type string/text or numeric for select by index');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {   
        return sprintf('Select in select %s[%s] by %s with value %s',
                            array_keys($this->step['select'])[0],
                            array_values($this->step['select'])[0],
                            $this->step['select']['by'],
                            $this->step['select']['value']
                        );
    }

}