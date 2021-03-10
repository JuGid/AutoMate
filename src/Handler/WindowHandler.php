<?php

namespace Automate\Handler;

class WindowHandler
{
    /**
     * @var array
     */
    public static $actual_windows = [];

    /**
     * @var array 
     */
    public static $previous_window_queue = [];

    public static function getWindows() : array
    {
        return self::$actual_windows;
    }

    public static function setWindows(array $windows) : void
    {
        self::$actual_windows = $windows;
    }

    public static function addPreviousWindow(string $previous_window) : void {
        self::$previous_window_queue[] = $previous_window;
    }

    public static function getPreviousWindow() : string {
        if(count(self::$previous_window_queue) == 1) {
            return self::$previous_window_queue[0];
        }
        
        $previous_window = array_pop(self::$previous_window_queue);
        return $previous_window;
    }

    public static function removeAll() : void
    {
        self::$actual_windows = [];
    }
}
