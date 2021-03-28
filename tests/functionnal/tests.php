#!/usr/bin/env php
<?php

/**
 * Self made tests to make functionnal tests
 * This can help to follow the basic behaviour of scenarios.
 * This is really basic and ugly but it reports what its needed
 */

require __DIR__.'/../../vendor/autoload.php';

use Automate\AutoMate;
use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\DriverConfiguration;
use Symfony\Component\Yaml\Yaml;

$configFile = __DIR__.'/config/config.yaml';
$results = [];
$scenariosTest = [
    'tables',
    'alert',
    'frame',
    'delayed-element',
    'slow-loading',
    'form'
];

//Should set a different port since chromedriver runs on 9515
//And run in headless mode
$driverConfiguration = new DriverConfiguration();
$driverConfiguration->setServerUrl('http://localhost:9515');
//$driverConfiguration->headlessMode();

//Create AutoMate instance
$autoMate = new AutoMate($configFile);
$autoMate->setDriverConfiguration($driverConfiguration);

//BEGIN PER CENT
$scenarioFolder = Configuration::get('scenario.folder');
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scenarioFolder));
$regex = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);
$usedCommands = [];
$numberOfCommands = 45;
$numberOfCommandsUsed = 0;

foreach ($regex as $file) {
    $scenarioFile = Yaml::parseFile($file[0]);
    $steps = $scenarioFile['scenario']['steps'];

    foreach($steps as $step) {
        if(!in_array(array_keys($step)[0], $usedCommands)) {
            $usedCommands[] = array_keys($step)[0];
            $numberOfCommandsUsed += 1;
        }
    }
}

sort($usedCommands);
$percent = ($numberOfCommandsUsed * 100) / $numberOfCommands;
$messageCommands = sprintf('Commands used : %d/%d (%.2f%%)',
                    $numberOfCommandsUsed,
                    $numberOfCommands,
                    $percent
                );
// END OF PERCENT

//BEGINNING TESTS
foreach ($scenariosTest as $scenario) {
    $results[$scenario] = $autoMate->run($scenario, false, true);
}
//END TESTS

//BEGIN REPORT
Console::report();
Console::separator();
Console::writeln($messageCommands, $percent > 80 ? 'green' : 'red');
Console::writeln(implode(',', $usedCommands));
Console::separator();
foreach ($results as $scenario=>$errorHandler) {
    Console::writeln('For scenario '.$scenario);
    $errorHandler->printErrors();
}
//END REPORT