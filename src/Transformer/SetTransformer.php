<?php

namespace Automate\Transformer;

use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;

class SetTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['set'=>[
            'varname' => ':any',
            'value' => ':any'
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        VariableRegistry::set(Scope::WORLD, $this->getStepData()['varname'], $this->getStepData()['value']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Set %s with value %s',
            $this->getStepData()['varname'],
            $this->getStepData()['value']
        );
    }
}
