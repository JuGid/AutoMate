#!/usr/bin/env php
<?php

/**
 * Self made tests to make functionnal tests
 * This can help to follow the basic behaviour of scenarios 
 */

require __DIR__.'/../../vendor/autoload.php';

use Automate\AutoMate;
use Automate\Console\Console;

$configFile = __DIR__.'/config/config.yaml';
$autoMate = new AutoMate($configFile);

$results = [];
$result['table'] = $autoMate->run('tables', false, true);
$result['alert'] = $autoMate->run('alert', false, true);

if(count($result) > 0) {
    Console::report();
    Console::separator();
    foreach($result as $scenario=>$errorHandler) {
        Console::writeln('For scenario '.$scenario);
        $errorHandler->printErrors();
    }
}