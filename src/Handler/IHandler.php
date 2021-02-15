<?php

namespace Automate\Handler;

interface IHandler {

    public static function add(string $name, string $value) : void;

    public static function remove(string $name) : void;

    public static function get(string $name) : string;

    public static function removeAll() : void;

    public static function isEmpty() : bool;
}