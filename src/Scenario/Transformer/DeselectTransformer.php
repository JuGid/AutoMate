<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\CommandException;
use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\WebDriverSelect;

/**
 * @codeCoverageIgnore
 */
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
        } elseif(is_numeric($deselectArray['value']) && $deselectArray['by'] == 'index') {
            $select->deselectByIndex($deselectArray['value']);
        } else {
            throw new CommandException('The deselect value has to be of type string/text or numeric for deselect by index');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {   
        return sprintf('Deselect in select %s[%s] by %s with value %s',
                            array_keys($this->step['deselect'])[0],
                            array_values($this->step['deselect'])[0],
                            $this->step['deselect']['by'],
                            $this->step['deselect']['value']
                        );
    }

}