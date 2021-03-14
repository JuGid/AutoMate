<?php

namespace Automate\Handler;

use Automate\Console\Console;
use Automate\AutoMateError;

final class ErrorHandler
{

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var int
     */
    private $wins = 0;

    /**
     * @var bool
     */
    private $shouldStoreDataset = false;

    public function win() : void
    {
        $this->wins++;
    }

    public function error(string $type, array $dataset = []) : void
    {
        $this->errors[] = new AutoMateError($type, $dataset);
    }

    public function countWins() : int
    {
        return $this->wins;
    }

    public function countErrors() : int
    {
        return count($this->errors, COUNT_NORMAL);
    }

    public function countErrorsType() : int
    {
        return count($this->getUniqueTypes(), COUNT_NORMAL);
    }

    public function getBackgroundColor() : string
    {
        return $this->countErrors() > 0 ? 'red' : 'green';
    }

    public function getShouldStoreDataset() : bool
    {
        return $this->shouldStoreDataset;
    }

    public function getUniqueTypes() : array
    {
        $uniqueErrorTypes = [];
        foreach ($this->errors as $error) {
            if (!in_array($error->getType(), $uniqueErrorTypes)) {
                $uniqueErrorTypes[] = $error->getType();
            }
        }
        return $uniqueErrorTypes;
    }

    public function getErrorsTypesWithDataset() : array
    {
        $errorsByType = [];
        foreach ($this->errors as $error) {
            $errorsByType[$error->getType()][] = $error->getDatasetAsString();
        }
        return $errorsByType;
    }

    public function shouldStoreDataset() : void
    {
        $this->shouldStoreDataset = true;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function __toString()
    {
        return sprintf(
            'Scenario with specification finished with Wins : %d and Errors : %d',
            $this->countWins(),
            $this->countErrors()
        );
    }

    /**
     * @codeCoverageIgnore
     *
     */
    public function printErrors() : void
    {
        $actualVerbose = Console::isVerbose();

        if (!$actualVerbose) {
            Console::setVerbose(true);
            Console::report();
            Console::separator();
        }

        if ($this->countErrors() == 0) {
            Console::writeln('NO ERROR', 'green');
            return;
        }

        if ($this->shouldStoreDataset) {
            $this->printErrorsTypeWithDataset();
        } else {
            $this->printErrorsTypeOnly();
        }

        Console::setVerbose($actualVerbose);
    }

    /**
     * @codeCoverageIgnore
     */
    public function printErrorsTypeOnly() : void
    {
        foreach ($this->getUniqueTypes() as $key=>$type) {
            Console::writeln(sprintf("[%d] %s", $key, $type), 'red');
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function printErrorsTypeWithDataset() : void
    {
        $errorsDataset = $this->getErrorsTypesWithDataset();

        foreach ($errorsDataset as $type=>$datasets) {
            Console::writeln($type, 'red');
            Console::separator('-');
            Console::writeln('Concerned dataset(s) : ');
            foreach ($datasets as $dataset) {
                Console::writeln("\t".$dataset);
            }
            Console::writeln('');
        }
    }
}
