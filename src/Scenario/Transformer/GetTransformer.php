<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\CommandException;
use Automate\Handler\VariableHandlerHandler;
use Automate\Scenario\Transformer\Helpers\WebLocator;

class GetTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     * @todo sendkeys and gettext to implement
     */
    protected function getPattern() : array
    {
        return ['get' => 
                    [
                        ':string :in("css","xpath","id","class","name","tag","linktext", "pltext")'=>':string',
                        'what'=>':string :in("text","attribute")',
                        'attribute?'=>':string',
                        'name'=>':string'
                    ]
                ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $element = WebLocator::get(array_keys($this->step['get'])[0], array_values($this->step['get'])[0]);
        $arrayGet = $this->step['get'];
        $value = null;
        if($arrayGet['what'] == 'text') {
            $value = $this->driver->findElement($element)->getText();
        } elseif($arrayGet['what'] == 'attribute') {
            if(!isset($arrayGet['attribute'])) {
                throw new CommandException('For get command if you use an attribute, you have to tell which one');
            }
            $value = $this->driver->findElement($element)->getAttribute($arrayGet['attribute']);
        }

        VariableHandlerHandler::set('global', $arrayGet['name'], $value);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $str = isset($this->step['get']['attribute']) ? 'with value ' . $this->step['get']['attribute'] : '';
        return sprintf('Get %s for element %s[%s] %s',
                            $this->step['get']['what'],
                            array_keys($this->step['get'])[0],
                            array_values($this->step['get'])[0],
                            $str
                        );
    }
}