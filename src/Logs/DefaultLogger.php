<?php 

namespace Automate\Logs;

class DefaultLogger extends AbstractLogger {

    /**
     * @param array<string, mixed> $dataset
     */
    public function log(array $dataset, string $log_type) : void {
        $this->write($dataset, $log_type);
    }
}