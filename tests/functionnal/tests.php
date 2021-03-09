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
use Automate\Driver\DriverConfiguration;

$configFile = __DIR__.'/config/config.yaml';
$results = [];
$scenariosTest = [
    'tables', 
    'alert',
    'frame'
];

//Should set a different port since chromedriver runs on 9515
//And run in headless mode
$driverConfiguration = new DriverConfiguration();
$driverConfiguration->setServerUrl('http://localhost:9515');
//$driverConfiguration->headlessMode();

//Create AutoMate instance
$autoMate = new AutoMate($configFile);
$autoMate->setDriverConfiguration($driverConfiguration);

//Begin
foreach ($scenariosTest as $scenario) {
    $results[$scenario] = $autoMate->run($scenario, false, true);
}

//Report every errors by scenario
Console::report();
Console::separator();
foreach ($results as $scenario=>$errorHandler) {
    Console::writeln('For scenario '.$scenario);
    $errorHandler->printErrors();
}
