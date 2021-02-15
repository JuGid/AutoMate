<?php 

namespace Automate\Logs;

class DefaultLogger extends AbstractLogger {

    /**
     * @param array<string,string> $dataset
     */
    public function log(array $dataset, string $log_type) : void {
        if($this->isEnable()) {
            if($log_type == LogType::LOG_WINS) {
                $this->addMessage('Finished with success.');
            }
            $this->write($dataset, $log_type);
        }  
    }
}