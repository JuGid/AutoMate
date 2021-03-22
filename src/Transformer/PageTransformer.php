<?php

namespace Automate\Transformer;

use Automate\Handler\PageHandler;

class PageTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['page'=>':string'];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        PageHandler::load($this->step['page']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Page %s loaded', $this->step['page']);
    }
}
