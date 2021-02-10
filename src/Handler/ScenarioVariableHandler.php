<?php 

namespace Automate\Handler;

use Automate\Exception\VariableAlreadyExistsException;
use Automate\Exception\VariableDoesNotExistException;

class ScenarioVariableHandler implements HandlerInterface {
    private static $variables;

    public static function add(string $name, $value) {
        if(isset(self::$variables[$name])) {
            throw new VariableAlreadyExistsException($name);
        }

        self::$variables[$name] = $value;
    }

    public static function addMultiple(...$names, ...$values) {
        for($i=0;$i<count($names); $i++) {
            self::add($names[$i], $values[$i]);
        }
    }

    public static function remove(string $name) {
        if(isset(self::$variables[$name])) {
            unset(self::$variables[$name]);
        }
    }

    public static function get(string $name) {
        if(!isset(self::$variables[$name])) {
            throw new VariableDoesNotExistException($name);
        }
        return self::$variables[$name];
    }
}