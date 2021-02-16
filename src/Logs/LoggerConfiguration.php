<?php 

namespace Automate\Logs;

use Automate\Handler\GlobalVariableHandler;

class LoggerConfiguration {

    /**
     * Columns to logs
     * 
     * @var array<string>
     */
    private $columns_log = [];

    /**
     * Logs directory path
     * 
     * @var string
     */
    private $logs_directory = "";

    /**
     * This name helps to have unique log file names
     * 
     * @var string
     */
    private $partial_name = "";

    public function __construct() {
        $this->partial_name = uniqid();
    }

    /**
     * Set the columns to log if all isn't needed
     * 
     * @param array $columns An array of string that specify the columns to log
     * @return self
     */
    public function setlogColumns(array $columns) : self{
        $this->columns_log = $columns;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getLogColumns() : array{
        return $this->columns_log;
    }

    public function hasColumnExceptions() : bool {
        return !empty($this->getLogColumns());
    }

    public function setLogsDirectory(string $directory) : self{
        $this->logs_directory = $directory;
        return $this;
    }

    public function getLogsDirectory() : string {
        return $this->logs_directory;
    }

    public function getPartialName() : string {
        return $this->partial_name;
    }

    /**
     * A bit redundant with getFilePathLogErrors but that's cool
     * 
     * @return string Fullpath of the log wins file
     */
    public function getFilepathLogWins() : string {
        return $this->getLogsDirectory() . '/' . GlobalVariableHandler::scenarioName() . '/LOGS_WINS_' . $this->getPartialName() . '.csv';
    }

    /**
     * A bit redundant with getFilePathLogWins but that's cool
     * 
     * @return string Fullpath of the log errors file
     */
    public function getFilepathLogErrors() : string {
        return $this->getLogsDirectory() . '/' . GlobalVariableHandler::scenarioName() . '/LOGS_ERRORS_' . $this->getPartialName() . '.csv';
    }

}