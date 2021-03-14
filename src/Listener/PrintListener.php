<?php 

namespace Automate\Listener;

use Automate\AutoMateEvents;
use Automate\AutoMateListener;
use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Scenario\Runner;

class PrintListener implements AutoMateListener {

    public function onEvent() {
        return [
            AutoMateEvents::RUNNER_SPEC_LINE,
            AutoMateEvents::RUNNER_SPEC_END,
            AutoMateEvents::RUNNER_SIMPLE_BEGIN,
            AutoMateEvents::RUNNER_SIMPLE_END,
            AutoMateEvents::RUNNER_ERROR,
            AutoMateEvents::STEP_TRANSFORM_ENDS
        ];
    }

    public function notify(string $event, $data) : int {
        $verboseMode = (int) Configuration::get('verbose');

        switch($event) {
            case AutoMateEvents::RUNNER_SPEC_LINE:
                if($verboseMode == Runner::VERBOSE_ALL) {
                    Console::writeBeginingLine($data['currentRow'], $data['totalRow'], $data['line']);
                }
                break;
            case AutoMateEvents::RUNNER_SPEC_END:
                if($verboseMode == Runner::VERBOSE_ALL || $verboseMode == Runner::VERBOSE_REPORT_ONLY) {
                    Console::endSpecification(
                        $data['errorHandler'],
                        $data['winFilepath'],
                        $data['errorFilepath'],
                        $data['testMode']
                    );
                }
                break;
            case AutoMateEvents::RUNNER_SIMPLE_BEGIN:
                if($verboseMode == Runner::VERBOSE_ALL) {
                    Console::writeBegining();
                }
                break;
            case AutoMateEvents::RUNNER_SIMPLE_END:
                if($verboseMode == Runner::VERBOSE_ALL || $verboseMode == Runner::VERBOSE_REPORT_ONLY) {
                    Console::endSimple($data['errorHandler'], $data['testMode']);
                }
                break;
            case AutoMateEvents::RUNNER_ERROR:
                if($verboseMode == Runner::VERBOSE_ALL) {
                    Console::writeEx($data['exception']);
                }
                break;
            case AutoMateEvents::STEP_TRANSFORM_ENDS:
                if($verboseMode == Runner::VERBOSE_ALL) {
                    Console::writeln($data['transformer']);
                }
        }
        return AutoMateListener::STATE_RECEIVED;
    }
}