<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\Cookie;

class CookieTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        return [
            'cookie' => [
                'name'=>':string',
                'value'=>':string'
                ]
            ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {   
        $cookie = new Cookie($this->step['cookie']['name'], $this->step['cookie']['value']);
        $this->driver->manage()->addCookie($cookie);
    }

}