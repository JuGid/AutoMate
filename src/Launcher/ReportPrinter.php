<?php 

namespace Automate\Launcher;

use Automate\Console\Console;
use Automate\Handler\ErrorHandler;

class ReportPrinter
{

    /**
     * The results as array of ErrorHandler
     * @var array
     */
    private $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function print() : void 
    {
        Console::report();
        Console::separator();
        foreach ($this->results as $scenario=>$errorHandler) {
            Console::writeln('For scenario '.$scenario);
            if($errorHandler instanceof ErrorHandler) {
                $errorHandler->printErrors();
            } else {
                echo 'General error returned. AutoMate returned : ', $errorHandler, "\n";
            }
        }
    }

    public function printInFile(string $file) : void 
    {
        $content = " _____                       _   \n";
        $content .= "|  __ \                     | |  \n";
        $content .= "| |__) |___ _ __   ___  _ __| |_ \n";
        $content .= "|  _  // _ \ '_ \ / _ \| '__| __|\n";
        $content .= "| | \ \  __/ |_) | (_) | |  | |_ \n";
        $content .= "|_|  \_\___| .__/ \___/|_|   \__|\n";
        $content .= "           | |                   \n";
        $content .= "           |_|                   \n";
        $content .= "____________________________________________________________\n";
        $content .= $file . "\n";
        $content .= "____________________________________________________________\n";
        foreach ($this->results as $scenario=>$errorHandler) {
            $content .= 'For scenario '. $scenario. "\n";

            if($errorHandler instanceof ErrorHandler) {

                if ($errorHandler->countErrors() == 0) {
                    $content .= "NO ERROR\n";
                } else {
                    foreach ($errorHandler->getUniqueTypes() as $key=>$type) {
                        $content .= sprintf("[%d] %s\n", $key, $type);
                    }
                }
            } else {
                $content .= 'General error returned. AutoMate returned : ' . $errorHandler . "\n";
                
            }
        }

        file_put_contents($file, $content);
    } 
}