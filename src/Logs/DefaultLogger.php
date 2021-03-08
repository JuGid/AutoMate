<?php

namespace Automate\Logs;

use Automate\Configuration\Configuration;

class DefaultLogger extends AbstractLogger
{

    /**
     * @param array<string,string> $dataset
     */
    public function log(array $dataset, string $log_type) : void
    {
        if (Configuration::get('logs.enable') === true) {
            if ($log_type == LogType::LOG_WINS) {
                $this->addMessage('Finished with success.');
            }
            $this->write($dataset, $log_type);
        }
    }
}
