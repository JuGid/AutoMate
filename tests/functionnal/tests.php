#!/usr/bin/env php
<?php

/**
 * Self made tests to make functionnal tests for AutoMate
 * This can help to follow the basic behaviour of scenarios.
 * This is really basic and ugly but it reports what its needed
 */

require __DIR__.'/../../vendor/autoload.php';

use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Symfony\Component\Yaml\Yaml;
use Automate\Launcher\Launcher;

$configFile = __DIR__.'/config/config.yaml';
$reportFile = __DIR__ . '/reports/report_' . (new DateTime())->format('dmY_His') . '.txt';


//BEGIN PER CENT
$scenarioFolder = Configuration::get('scenario.folder');
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scenarioFolder));
$regex = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);
$usedCommands = [];
$numberOfCommands = 46;
$numberOfCommandsUsed = 0;

function addToArray($elem, array &$array, int &$numberCommands)
{
    foreach ($elem as $stp) {
        if (!in_array(array_keys($stp)[0], $array)) {
            $array[] = array_keys($stp)[0];
            $numberCommands += 1;
        }
    }
}

//WOW, Beautiful !!!!!
foreach ($regex as $file) {
    $scenarioFile = Yaml::parseFile($file[0]);
    $steps = $scenarioFile['scenario']['steps'];

    foreach ($steps as $step) {
        if (!in_array(array_keys($step)[0], $usedCommands)) {
            $usedCommands[] = array_keys($step)[0];
            $numberOfCommandsUsed += 1;
        }

        if (array_keys($step)[0] == 'loop') {
            addToArray($step['loop']['steps'], $usedCommands, $numberOfCommandsUsed);
        }
        
        if (array_keys($step)[0] == 'condition') {
            addToArray($step['condition']['correct']['steps'], $usedCommands, $numberOfCommandsUsed);
            addToArray($step['condition']['incorrect']['steps'], $usedCommands, $numberOfCommandsUsed);
        }
    }
}

sort($usedCommands);
$percent = ($numberOfCommandsUsed * 100) / $numberOfCommands;
$messageCommands = sprintf(
    'Commands used : %d/%d (%.2f%%)',
    $numberOfCommandsUsed,
    $numberOfCommands,
    $percent
);
// END OF PERCENT

$launcher = new Launcher($configFile);
$launcher->add('tables')
         ->add('alert')
         ->add('frame')
         ->add('delayed-element')
         ->add('slow-loading')
         ->add('form')
         ->add('general')
         ->add('real')
         ->add('set');
$launcher->run();
$launcher->printReport();
Console::writeln($messageCommands, $percent > 80 ? 'green' : 'red');
Console::writeln(implode(',', $usedCommands));
