<?php 

namespace Automate\Handler;

use Automate\Exception\VariableCallException;

class VariableHandlerHandler {

    public static function set(string $from, string $name, string $value) : void {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            $class::add($name, $value);
        } else {
            throw new VariableCallException('The scope ' . $from . ' does not exist');
        }
    }

    public static function get(string $from, string $name) : string {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            return $class::get($name);
        } else {
            throw new VariableCallException('The scope ' . $from . ' does not exist');
        }
    }

    public static function remove(string $from, string $name) : void {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            $class::remove($name);
        } else {
            throw new VariableCallException('The scope ' . $from . ' does not exist');
        }
    }

    public static function removeAll(string $from) : void {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            $class::removeAll();
        } else {
            throw new VariableCallException('The scope ' . $from . ' does not exist');
        }
    }

    public static function isEmpty(string $from) : bool {
        $class = __NAMESPACE__ . '\\'.ucfirst($from) . 'VariableHandler';
        if(class_exists($class)) {
            return $class::isEmpty();
        } else {
            throw new VariableCallException('The scope ' . $from . ' does not exist');
        }
    }
}