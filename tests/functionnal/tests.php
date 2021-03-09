#!/usr/bin/env php
<?php

/**
 * Self made tests to make functionnal tests
 * This can help to follow the basic behaviour of scenarios.
 * This is really basic and ugly but it reports what its needed
 */

require __DIR__.'/../../vendor/autoload.php';

use Automate\AutoMate;
use Automate\Console\Console;

$configFile = __DIR__.'/config/config.yaml';
$results = [];
$scenariosTest = ['tables', 'alert'];
$autoMate = new AutoMate($configFile);

foreach ($scenariosTest as $scenario) {
    $results[$scenario] = $autoMate->run($scenario, false, true);
}

Console::report();
Console::separator();
foreach ($results as $scenario=>$errorHandler) {
    Console::writeln('For scenario '.$scenario);
    $errorHandler->printErrors();
}
