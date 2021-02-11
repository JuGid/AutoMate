<?php 

namespace Automate\Handler;

use Automate\Exception\VariableException;

class SpecVariableHandler implements IHandler {
    private static $variables;

    public static function add(string $name, $value) {
        if(isset(self::$variables[$name])) {
            throw new VariableException('Variable ' . $name . ' already exists');
        }

        self::$variables[$name] = $value;
    }

    public static function remove(string $name) {
        if(isset(self::$variables[$name])) {
            unset(self::$variables[$name]);
        }
    }

    public static function get(string $name) {
        if(!isset(self::$variables[$name])) {
            throw new VariableException('Variable ' . $name . ' does not exist');
        }
        return self::$variables[$name];
    }

    public static function load(array $data) {
        self::$variables = [];
        self::$variables = array_merge(self::$variables, $data);
    }
}