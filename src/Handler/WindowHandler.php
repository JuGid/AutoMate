<?php

namespace Automate\Handler;

class WindowHandler {

    /**
     * @var array
     */
    public static $actual_windows = [];

    public static function getWindows() : array {
        return self::$actual_windows;
    }

    public static function setWindows(array $windows) : void {
        self::$actual_windows = $windows;
    }

    public static function removeAll() : void {
        self::$actual_windows = [];
    }
}