<?php

namespace Automate\Transformer\Logic;

use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use InvalidArgumentException;

abstract class Logic
{
    protected const VARIABLE_DEPTH = 2;

    protected const UNANSWERED = -1;
    protected const INVALID = 0;
    protected const VALID = 1;
    
    /**
     * Get a variable from the variable registry with pattern 'scope.name'
     */
    public function get(string $accessor) : string
    {
        $access = explode(".", $accessor);

        if (count($access) != self::VARIABLE_DEPTH) {
            throw new InvalidArgumentException('You should call a variable by \'scope.name\'');
        }

        return VariableRegistry::get($access[0], $access[1]);
    }

    public function getWorldVariable(string $variable) : string
    {
        return VariableRegistry::get(Scope::WORLD, $variable);
    }

    public function getSpecVariable(string $variable) : string
    {
        return VariableRegistry::get(Scope::SPEC, $variable);
    }

    public function getScenarioVariable(string $variable) : string
    {
        return VariableRegistry::get(Scope::SCENARIO, $variable);
    }

    abstract public function answeredBy() : bool;
}
