<?php 

namespace Automate\Handler;

use Automate\Exception\BadVariableCallException;

class VariableHandlerHandler {

    public static function set(string $from, string $name, $value) {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            $class::add($name, $value);
        } else {
            throw new BadVariableCallException($from . '.' . $name);
        }
    }

    public static function get(string $from, string $name) {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            return $class::get($name);
        } else {
            throw new BadVariableCallException($from . '.' . $name);
        }
    }

    public static function remove(string $from, string $name) {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            $class::remove($name);
        } else {
            throw new BadVariableCallException($from . '.' . $name);
        }
    }
}