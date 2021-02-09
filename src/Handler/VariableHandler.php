<?php 

namespace Automate\Handler;

class VariableHandler {
    private static $variables;

    public static function add(string $name, $value) {
        self::$variables[$name] = $value;
    }

    public static function remove(string $name) {
        unset(self::$variables[$name]);
    }

    public static function get(string $name) {
        return self::$variables[$name];
    }
}