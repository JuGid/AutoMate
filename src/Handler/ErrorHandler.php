<?php 

namespace Automate\Handler;

use Automate\Console\Console;

class ErrorHandler {

    private $errors = [];

    private $wins = 0;

    public function __construct(){}

    public function win() : void {
        $this->wins++;
    }

    public function error(array $dataset, string $type) : void {
        $this->errors[$type][] = $dataset;
    }

    public function countErrorsType() : int {
        return count($this->errors);
    }

    public function countErrors() : int {
        $count = 0;
        foreach($this->errors as $errorList) {
            $count += count($errorList);
        }
        return $count;
    }

    public function countWins() : int {
        return $this->wins;
    }

    public function getBackgroundColor() : string {
        return $this->countErrors()>0 ? 'red' : 'green';
    }

    public function __toString() {
        return sprintf('Scenario with specification finished with Wins : %d and Errors : %d', 
            $this->countWins(), 
            $this->countErrors()
        );
    }

    /**
     * codeCoverageIgnore
     */
    public function printErrors() : void {
        if($this->countErrors()) {
            foreach($this->errors as $type=>$datasets) {
                Console::writeln($type, 'red');
                Console::separator('-');
                $this->printErrorForType($type);
                Console::separator('-');
            }
        } else {
            Console::writeln("No error", 'green', null);
        }
    }

    /**
     * codeCoverageIgnore
     */
    public function printErrorForType(string $type) {
        foreach($this->errors[$type] as $key=>$dataset) {
            Console::writeln(sprintf("\t[%d] : %s", $key, implode(',', $dataset)));
        }
    }
}