<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\NotAValidCommandException;
use Automate\Handler\VariableHandler;
use Automate\Handler\VariableHandlerHandler;
use Automate\Scenario\Transformer\Helpers\WebLocator;

class GetTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     * @todo sendkeys and gettext to implement
     */
    protected function getPattern()
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
    protected function transform() 
    {   
        $element = WebLocator::get(array_keys($this->step['get'])[0], array_values($this->step['get'])[0]);
        $arrayGet = $this->step['get'];
        
        if($arrayGet['what'] == 'text') {
            $value = $this->driver->findElement($element)->getText();
        } elseif($arrayGet['what'] == 'attribute') {
            if(!isset($arrayGet['attribute'])) {
                throw new NotAValidCommandException('get');
            }
            $value = $this->driver->findElement($element)->getAttribute($arrayGet['attribute']);
        }

        VariableHandlerHandler::set('global', $value, $arrayGet['name']);
    }

}