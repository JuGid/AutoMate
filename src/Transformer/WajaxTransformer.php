<?php

namespace Automate\Transformer;

use Automate\Configuration\Configuration;

class WajaxTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['wajax'=>':string'];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        if (!substr(trim($this->step['wajax']), -1) == ';') {
            $this->step['wajax'] = trim($this->step['wajax']).';';
        }

        $this->driver->wait(Configuration::get('wait.for'), Configuration::get('wait.every'))
                     ->until(function ($driver) {
                         return $driver->executeScript($this->step['wajax']);
                     }, 'Your script returned false');
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Waiting with script %s', $this->step['wajax']);
    }
}
