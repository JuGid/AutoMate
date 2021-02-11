<?php

namespace Automate\Handler;

interface IHandler {

    public static function add(string $name, $value);

    public static function remove(string $name);

    public static function get(string $name);
}