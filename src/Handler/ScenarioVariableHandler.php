<?php 

namespace Automate\Handler;

use Automate\Exception\VariableException;

class ScenarioVariableHandler implements IHandler {

    /**
     * @var array<string,string>
     */
    public static $variables;

    public static function add(string $name, string $value) : void {
        if(isset(self::$variables[$name])) {
            throw new VariableException('Variable ' . $name . ' already exists');
        }

        self::$variables[$name] = $value;
    }

    public static function remove(string $name) : void{
        if(!isset(self::$variables[$name])) {
            throw new VariableException('Variable ' . $name . ' cannot be removed as it does not exist');
        }
        unset(self::$variables[$name]);
    }

    public static function removeAll() : void {
        foreach(self::$variables as $key=>$value) {
            self::remove($key);
        }
    }

    public static function get(string $name) : string {
        if(!isset(self::$variables[$name])) {
            throw new VariableException('Variable ' . $name . ' does not exist');
        }
        return self::$variables[$name];
    }

    public static function isEmpty() : bool{
        return empty(self::$variables);
    }
    
}