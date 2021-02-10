<?php 

namespace Automate\Handler;

use Automate\Exception\VariableAlreadyExistsException;
use Automate\Exception\VariableDoesNotExistException;

class ScenarioVariableHandler implements HandlerInterface {
    static $variables;

    public static function add(string $name, $value) : void{
        if(isset(self::$variables[$name])) {
            throw new VariableAlreadyExistsException($name);
        }

        self::$variables[$name] = $value;
    }

    public static function remove(string $name) : void{
        if(isset(self::$variables[$name])) {
            unset(self::$variables[$name]);
        }
    }

    public static function get(string $name) : string{
        if(!isset(self::$variables[$name])) {
            throw new VariableDoesNotExistException($name);
        }
        return self::$variables[$name];
    }
}