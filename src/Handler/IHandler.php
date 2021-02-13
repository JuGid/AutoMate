<?php

namespace Automate\Handler;

interface IHandler {

    public static function add(string $name, string $value) : void;

    public static function remove(string $name) : void;

    public static function get(string $name) : string;
}