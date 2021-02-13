<?php 

namespace Automate\Logs;

class DefaultLogger extends AbstractLogger {

    public function log(array $dataset, string $log_type){
        $this->write($dataset, $log_type);
    }
}