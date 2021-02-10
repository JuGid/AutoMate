<?php

namespace Automate\Handler;

interface HandlerInterface {

    public static function add(string $name, $value);

    public static function remove(string $name);

    public static function get(string $name);
}