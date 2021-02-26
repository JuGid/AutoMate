<?php 

namespace Automate\Console;

use Automate\Handler\ErrorHandler;

/**
 * Simple class to help writting things in terminal
 * 
 * @codeCoverageIgnore
 */
class Console {

    public static function writeBeginingLine(int $current, int $on, array $dataset) {
        self::separator();
        self::logo();
        self::writeln("Line ". $current .'/'. $on);
        self::writeln("Data set : " . implode(',',$dataset));
        self::separator();
    }

    public static function writeEndingSpecification(ErrorHandler $errorHandler, string $logfileWins, string $logfileErrors) {
        self::end();
        self::writeln($errorHandler, null, $errorHandler->getBackgroundColor());
        self::separator('=');
        self::writeln("Logs can be found at :");
        self::writeln("* LOGS_WIN : " . $logfileWins);
        self::writeln("* LOGS_ERRORS : " . $logfileErrors);
        self::separator('='); 
    }

    public static function logo() {
        self::writeln("    /\        | |      |  \/  |     | |      ");
        self::writeln("   /  \  _   _| |_ ___ | \  / | __ _| |_ ___ ");
        self::writeln("  / /\ \| | | | __/ _ \| |\/| |/ _` | __/ _ \ ");
        self::writeln(" / ____ \ |_| | || (_) | |  | | (_| | ||  __/ ");
        self::writeln("/_/    \_\__,_|\__\___/|_|  |_|\__,_|\__\___| ");
    }

    public static function start() {
        self::separator();
        self::writeln(" __ _             _   ");
        self::writeln("/ _\ |_ __ _ _ __| |_ ");
        self::writeln("\ \| __/ _` | '__| __|");
        self::writeln("_\ \ || (_| | |  | |_ ");
        self::writeln("\__/\__\__,_|_|   \__|");
        self::separator();
    }

    public static function end() {
        self::separator('=');
        echo "___ _  _ ___  \n| __| \| |   \ \n| _|| .` | |) |\n|___|_|\_|___/    \n";
        self::separator('=');
    }

    public static function separator($separator = '_', int $size = 60) {
        $str = '';
        for($i=0; $i<$size;$i++) {
            $str.=$separator;
        }
        self::writeln($str);
    }

    public static function writeln(string $str, string $foreground = null, string $background = null) {
        if($foreground !== null || $background !== null) {
            $colors = new Colors();
            $str = $colors->getColoredString($str, $foreground, $background);
        }

        echo sprintf("%s\n", $str);
    }

    public static function writeEx(\Exception $e) {
        self::writeln($e->getMessage());
    }
}