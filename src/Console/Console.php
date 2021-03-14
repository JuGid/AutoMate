<?php

namespace Automate\Console;

use Automate\Handler\ErrorHandler;

/**
 * Simple class to help writting things in terminal
 *
 * @codeCoverageIgnore
 */
class Console
{
    public static function writeBeginingLine(int $current, int $on, array $dataset)
    {
        self::separator();
        self::logo();
        self::writeln("Line ". $current .'/'. $on);
        self::writeln("Data set : " . implode(',', $dataset));
        self::separator();
    }

    public static function writeBegining()
    {
        self::separator();
        self::logo();
        self::separator();
    }

    public static function endSpecification(ErrorHandler $errorHandler, string $logfileWins, string $logfileErrors, bool $testMode)
    {
        self::separator();
        self::writeln((string) $errorHandler, null, $errorHandler->getBackgroundColor());
        self::separator('=');
        if ($testMode) {
            $errorHandler->printErrors();
            self::separator('=');
        }
        self::writeln("Logs can be found at :");
        self::writeln("* LOGS_WINS : " . $logfileWins);
        self::writeln("* LOGS_ERRORS : " . $logfileErrors);
        self::separator('=');
    }

    public static function endSimple(ErrorHandler $errorHandler, bool $testMode)
    {
        self::separator('=');
        if ($testMode) {
            $errorHandler->printErrors();
            self::separator('=');
        }
    }

    public static function logo()
    {
        self::writeln("    /\        | |      |  \/  |     | |      ");
        self::writeln("   /  \  _   _| |_ ___ | \  / | __ _| |_ ___ ");
        self::writeln("  / /\ \| | | | __/ _ \| |\/| |/ _` | __/ _ \ ");
        self::writeln(" / ____ \ |_| | || (_) | |  | | (_| | ||  __/ ");
        self::writeln("/_/    \_\__,_|\__\___/|_|  |_|\__,_|\__\___| ");
    }

    public static function end()
    {
        self::separator('=');
        self::writeln(" ___ _  _ ___");
        self::writeln("| __| \| |   \ ");
        self::writeln("| _|| .` | |) |");
        self::writeln("|___|_|\_|___/");
        self::separator('=');
    }

    public static function report()
    {
        self::writeln(" _____                       _   ");
        self::writeln("|  __ \                     | |  ");
        self::writeln("| |__) |___ _ __   ___  _ __| |_ ");
        self::writeln("|  _  // _ \ '_ \ / _ \| '__| __|");
        self::writeln("| | \ \  __/ |_) | (_) | |  | |_ ");
        self::writeln("|_|  \_\___| .__/ \___/|_|   \__|");
        self::writeln("           | |                   ");
        self::writeln("           |_|                   ");
    }

    public static function separator($separator = '_', int $size = 60)
    {
        self::writeln(str_repeat($separator, $size));
    }

    public static function writeln(string $str, string $foreground = null, string $background = null)
    {
            echo sprintf("%s\n", (new Colors())->getColoredString($str, $foreground, $background));
    }

    public static function writeEx(\Exception $e)
    {
        self::writeln($e->getMessage(), 'red');
    }
}
