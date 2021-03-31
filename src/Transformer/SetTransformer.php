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
            'value' => ':any',
            ':string :in("add", "substract") ?' => ':number'
        ]];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $value = $this->getStepData()['value'];
        $keys = array_keys($this->getStepData());

        if (isset($keys[2])) {
            $valueToOperate = intval($this->getStepData()[$keys[2]]);
            switch($keys[2]) {
                case 'add':
                    $value += $valueToOperate;
                    break;
                case 'substract':
                    $value -= $valueToOperate;
                    break;
            }
        }

        VariableRegistry::set(Scope::WORLD, $this->getStepData()['varname'], $value);
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
