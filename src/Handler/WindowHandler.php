<?php

namespace Automate\Handler;

class WindowHandler {

    public static $actual_windows;

    public static function getWindows() {
        return self::$actual_windows;
    }

    public static function setWindows($windows) {
        self::$actual_windows = $windows;
    }
}