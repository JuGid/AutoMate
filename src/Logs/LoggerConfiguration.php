<?php 

namespace Automate\Logs;

class LoggerConfiguration {

    /**
     * Columns to logs
     * 
     * @var array
     */
    private $columns_log = [];

    /**
     * Logs directory path
     * 
     * @var string
     */
    private $logs_directory = "";

    /**
     * The name of the scenario running
     */
    private $scenario_name = "";

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

    public function getLogColumns() {
        return $this->columns_log;
    }

    public function hasColumnExceptions() {
        return !empty($this->getLogColumns());
    }

    public function setLogsDirectory(string $directory) : self{
        $this->logs_directory = $directory;
        return $this;
    }

    public function getLogsDirectory() {
        return $this->logs_directory;
    }

    public function setScenarioName(string $name) {
        $this->scenario_name = $name;
    }

    public function getScenarioName() {
        return $this->scenario_name;
    }

    public function getPartialName() {
        return $this->partial_name;
    }

    /**
     * A bit redundant with getFilePathLogErrors but that's cool
     * 
     * @return string Fullpath of the log wins file
     */
    public function getFilepathLogWins() {
        return $this->getLogsDirectory() . '/' . $this->getScenarioName() . '/LOGS_WINS_' . $this->getPartialName() . '.csv';
    }

    /**
     * A bit redundant with getFilePathLogWins but that's cool
     * 
     * @return string Fullpath of the log errors file
     */
    public function getFilepathLogErrors() {
        return $this->getLogsDirectory() . '/' . $this->getScenarioName() . '/LOGS_ERRORS_' . $this->getPartialName() . '.csv';
    }

}