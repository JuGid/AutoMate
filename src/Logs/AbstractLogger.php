<?php 

namespace Automate\Logs;

use Automate\Exception\LogException;

/**
 * @todo Create a log file manager ???
 */
abstract class AbstractLogger {

    /**
     * @var LoggerConfiguration
     */
    private $configuration;

    /**
     * The logger message queue to add when scenario is logged
     * 
     * @var array
     */
    private $messageQueue = [];

    /**
     * Pointer to log file errors
     */
    protected $file_e;

    /**
     * Pointer to log file wins
     */
    protected $file_w;

    public function __construct(string $directory = "") {
        $this->configuration = new LoggerConfiguration();
        $this->configuration->setLogsDirectory($directory);
    }

    public function init(string $log_directory, string $scenario_name, array $spec_header) {
        $this->getConfiguration()->setLogsDirectory($log_directory);
        $this->getConfiguration()->setScenarioName($scenario_name);

        $this->file_e = fopen($this->getConfiguration()->getFilepathLogErrors(), 'a');
        $this->file_w = fopen($this->getConfiguration()->getFilepathLogWins(), 'a');
        
        if($this->file_e === false || $this->file_w === false) {
            throw new LogException('A file for log failed to open. Please check that the folder [path_to_log]/[scenario] exists.');
        }

        if(!$this->getConfiguration()->hasColumnExceptions()) {
            array_push($spec_header, 'message');
            $this->getConfiguration()->setlogColumns($spec_header);
        } else {
            $sorted_log_columns = [];
            foreach($spec_header as $value) {
                if(in_array($value, $this->getConfiguration()->getLogColumns())) {
                    $sorted_log_columns[] = $value;
                }
            }
            array_push($sorted_log_columns, 'message');
            $this->getConfiguration()->setlogColumns($sorted_log_columns);
        }

        fputcsv($this->file_e, $this->getConfiguration()->getLogColumns());
        fputcsv($this->file_w, $this->getConfiguration()->getLogColumns());
    }

    /**
     * Add a message to the queue
     */
    public function addMessage(string $message) : self {
        array_push($this->messageQueue, $message);
        return $this;
    }
    
    /**
     * This function returns the messages in queue et reset it.
     */
    public function getMessages() : string{
        $messages = $this->messageQueue;
        $this->messageQueue = [];
        return implode(',',$messages);
    }

    public function setConfiguration(LoggerConfiguration $configuration) {
        $this->configuration = $configuration;
    }

    public function getConfiguration() {
        return $this->configuration;
    }

    protected function write(array $data, string $log_type) {
        $data_to_write = [];
        foreach($data as $key=>$value) {
            if(in_array($key, $this->getConfiguration()->getLogColumns())) {
                $data_to_write[] = $value;
            }
        }

        array_push($data_to_write, $this->getMessages());

        if($log_type == LogType::LOG_ERRORS) {
            fputcsv($this->file_e, $data_to_write);
        } elseif($log_type == LogType::LOG_WINS) {
            fputcsv($this->file_w,$data_to_write);
        }

    }
    
    public function end() {
        fclose($this->file_e);
        fclose($this->file_w);
    }

    abstract public function log(array $dataset, string $log_type);

}