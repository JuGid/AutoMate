<?php 

namespace Automate\Console;

/**
 * Simple class to help writting things in terminal
 */
class Console {

    public static function writeBeginingLine(int $current, int $on, array $dataset) {
        self::separator();
        self::logo();
        self::writeln("Line ". $current .'/'. $on);
        self::writeln("Data set : " . implode(',',$dataset));
        self::separator();
    }

    public static function writeEndingSpecification(int $wins, int $errors, string $logfileWins, string $logfileErrors) {
        Console::end();
        Console::writeln('Scenario with specification finished with Wins : '.$wins . ' / Errors : '. $errors);
        Console::separator('=');
        Console::writeln("Logs can be found at :");
        Console::writeln("* LOGS_WIN : " . $logfileWins);
        Console::writeln("* LOGS_ERRORS : " . $logfileErrors);
        Console::separator('='); 
    }

    public static function logo() {
        echo "    /\        | |      |  \/  |     | |      \n";
        echo "   /  \  _   _| |_ ___ | \  / | __ _| |_ ___ \n";
        echo "  / /\ \| | | | __/ _ \| |\/| |/ _` | __/ _ \ \n";
        echo " / ____ \ |_| | || (_) | |  | | (_| | ||  __/ \n";
        echo "/_/    \_\__,_|\__\___/|_|  |_|\__,_|\__\___| \n";
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

    public static function writeln(string $str) {
        echo sprintf("%s\n", $str);
    }

    public static function writeEx(\Exception $e) {
        self::writeln($e->getMessage());
        exit(1);
    }
}