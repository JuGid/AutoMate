<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;
use Facebook\WebDriver\Cookie;

class CookieTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
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
    protected function transform() : void 
    {   
        $cookie = new Cookie($this->step['cookie']['name'], $this->step['cookie']['value']);
        $this->driver->manage()->addCookie($cookie);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Create a cookie with %s:%s',
                            $this->step['cookie']['name'],
                            $this->step['cookie']['value']
                        );
    }
}