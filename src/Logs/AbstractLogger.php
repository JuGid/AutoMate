<?php 

namespace Automate\Logs;

use Automate\Configuration\Configuration;
use Automate\Exception\LogException;
use Automate\Handler\GlobalVariableHandler;

/**
 * @todo Create a log file manager ???
 */
abstract class AbstractLogger {

    /**
     * The logger message queue to add when scenario is logged
     * 
     * @var array<string>
     */
    private $messageQueue = [];

    /**
     * Pointer to log file errors
     * 
     * @var resource
     */
    protected $file_e;

    /**
     * Pointer to log file wins
     * 
     * @var resource
     */
    protected $file_w;

    /**
     * This name helps to have unique log file names
     * 
     * @var string
     */
    private $partialName = "";

    public function __construct(array $header) {
        $this->partialName = uniqid();
        $this->file_e = fopen($this->getFilepath(LogType::LOG_ERRORS), 'a');
        $this->file_w = fopen($this->getFilepath(LogType::LOG_WINS), 'a');

        if($this->file_e === false || $this->file_w === false) {
            throw new LogException('A file for log failed to open. Please check that the folder [path_to_log]/[scenario] exists.');
        }
        
        array_push($header, 'message');
        Configuration::logsColumns($header);

        fputcsv($this->file_e, Configuration::get('logs.columns'));
        fputcsv($this->file_w, Configuration::get('logs.columns'));
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
    public function getMessages() : string {
        $messages = $this->messageQueue;
        $this->messageQueue = [];
        return implode(',',$messages);
    }

    protected function write(array $data, string $log_type) : void{
        $data_to_write = [];
        foreach($data as $key=>$value) {
            if(in_array($key, Configuration::get('logs.columns'))) {
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
    
    public function end() : void{
        if($this->file_e === null || $this->file_w === null) {
            throw new LogException('Use init() to set logs files');
        }

        if($this->file_e === false || $this->file_w === false) {
            throw new LogException('Cannot close a file that is not open.');
        }
        
        fclose($this->file_e);
        fclose($this->file_w);
    }

    public function getFilepath(string $logType) {
        return sprintf('%s/%s/%s_%s.csv',
            Configuration::get('logs.folder'),
            GlobalVariableHandler::scenarioName(),
            $logType,
            $this->partialName
        );
    }

    /**
     * @param array<string,string> $dataset
     */
    abstract public function log(array $dataset, string $log_type) : void;

}