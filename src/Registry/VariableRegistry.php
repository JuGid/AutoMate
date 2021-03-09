<?php

namespace Automate\Registry;

use InvalidArgumentException;

abstract class VariableRegistry
{
    private const INVALID_SCOPE = 'Only scopes world, spec and scenario are possible.';

    /**
     * @var array
     */
    private static $scopesAllowed = [
        Scope::SCENARIO,
        Scope::SPEC,
        Scope::WORLD,
        Scope::TEST
    ];

    /**
     * @var array
     */
    private static $variables = [];

    public static function get(string $scope, string $name) : string
    {
        if (!in_array($scope, self::$scopesAllowed) || !isset(self::$variables[$scope][$name])) {
            throw new InvalidArgumentException(self::INVALID_SCOPE);
        }

        return self::$variables[$scope][$name];
    }

    public static function set(string $scope, string $name, string $value) : void
    {
        if (!in_array($scope, self::$scopesAllowed)) {
            throw new InvalidArgumentException(self::INVALID_SCOPE);
        }

        self::$variables[$scope][$name]=$value;
    }

    public static function setMultiple(string $scope, array $data)
    {
        if (!in_array($scope, self::$scopesAllowed)) {
            throw new InvalidArgumentException(self::INVALID_SCOPE);
        }

        self::$variables[$scope] = array_merge(self::$variables[$scope], $data);
    }

    public static function reset(string $scope) : void
    {
        if (!in_array($scope, self::$scopesAllowed)) {
            throw new InvalidArgumentException(self::INVALID_SCOPE);
        }

        unset(self::$variables[$scope]);
        self::$variables[$scope]=[];
    }

    public static function isEmpty(string $scope) : bool
    {
        if (!in_array($scope, self::$scopesAllowed)) {
            throw new InvalidArgumentException(self::INVALID_SCOPE);
        }

        return empty(self::$variables[$scope]);
    }
}
