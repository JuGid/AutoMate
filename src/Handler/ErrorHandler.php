<?php 

namespace Automate\Handler;

use Automate\Console\Console;

class ErrorHandler {

    private $errors = [];

    private $wins = 0;

    private $shouldStoreDataset = false;

    public function __construct(){}

    public function win() : void {
        $this->wins++;
    }

    public function error(string $type, array $dataset = []) : void {
        if($this->shouldStoreDataset) {
            $this->errors[$type][] = implode(",", $dataset);
        } else {
            $this->errors[] = $type;
        }
    }

    public function countWins() : int {
        return $this->wins;
    }

    public function countErrors() : int {
        if($this->shouldStoreDataset) {
            return count($this->errors, COUNT_RECURSIVE) - $this->countErrorsType();
        }
        return $this->countErrorsType();
    }

    public function countErrorsType() : int {
        return count($this->errors, COUNT_NORMAL);
    }

    public function getBackgroundColor() : string {
        return $this->countErrors() > 0 ? 'red' : 'green';
    }

    public function getShouldStoreDataset() : bool {
        return $this->shouldStoreDataset;
    }

    public function shouldStoreDataset() : void {
        $this->shouldStoreDataset = true;
    }

    public function __toString() {
        return sprintf('Scenario with specification finished with Wins : %d and Errors : %d', 
            $this->countWins(), 
            $this->countErrors()
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function printErrors() : void {
        if($this->countErrors() == 0 ) {
            Console::writeln('NO ERROR', 'green');
            return;
        }

        if($this->shouldStoreDataset) {
            $this->printErrorsTypeWithDataset();
        } else {
            $this->printErrorsTypeOnly();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function printErrorsTypeOnly() : void {
        foreach($this->errors as $key=>$type) {
            Console::writeln(sprintf("[%d] %s", $key, $type), 'red');
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function printErrorsTypeWithDataset() : void {
        foreach($this->errors as $type=>$datasets) {
            Console::writeln($type, 'red');
            Console::separator('-');
            foreach($datasets as $dataset) {
                Console::writeln("\t".$dataset);
            }
        }
    }
   
}