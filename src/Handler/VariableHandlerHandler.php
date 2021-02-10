<?php 

namespace Automate\Handler;

use Automate\Exception\BadVariableCallException;

class VariableHandlerHandler {

    public static function set(string $from, string $name, $value) {
        if(class_exists(ucfirst($from) . 'VariableHandler')) {
            ucfirst($from) . 'VariableHandler'::add($name, $value);
        } else {
            throw new BadVariableCallException($from . '.' . $name);
        }
    }

    public static function get(string $from, string $name) {
        if(class_exists(ucfirst($from) . 'VariableHandler')) {
            ucfirst($from) . 'VariableHandler'::get($name);
        } else {
            throw new BadVariableCallException($from . '.' . $name);
        }
    }

    public static function remove(string $from, string $name) {
        if(class_exists(ucfirst($from) . 'VariableHandler')) {
            ucfirst($from) . 'VariableHandler'::remove($name);
        } else {
            throw new BadVariableCallException($from . '.' . $name);
        }
    }
}