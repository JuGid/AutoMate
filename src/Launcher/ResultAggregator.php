<?php 

namespace Automate\Launcher;

use Automate\Handler\ErrorHandler;
use InvalidArgumentException;

class ResultAggregator {

    private $results = [];

    public function addResult(string $scenario, $result) : void
    {
        $this->results[$scenario] = $result;
    }

    public function hasError() : bool {
        foreach($this->results as $errorHandlerResult) 
        {
            if($errorHandlerResult instanceOf ErrorHandler && $errorHandlerResult->countErrors() > 0) {
                return true;
            }
        }

        return false;
    }

    public function printReports() : void 
    {
        $reportPrinter = new ReportPrinter($this->results);
        $reportPrinter->print();
    }

    public function printReportInFile(string $file) : void 
    {
        if (pathinfo($file, PATHINFO_EXTENSION) != 'txt') 
        {
            throw new InvalidArgumentException('The report file must have a txt extension');
        }

        $reportPrinter = new ReportPrinter($this->results);
        $reportPrinter->printInFile($file);
        
    }
}