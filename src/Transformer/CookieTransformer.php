<?php

namespace Automate\Transformer;

use Facebook\WebDriver\Cookie;

class CookieTransformer extends AbstractTransformer
{

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
     *
     * @codeCoverageIgnore
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
        return sprintf(
            'Create a cookie with %s:%s',
            $this->step['cookie']['name'],
            $this->step['cookie']['value']
        );
    }
}
