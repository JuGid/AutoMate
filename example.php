#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Automate\Scenario\ScenarioRunner;

$scenarioRunner = new ScenarioRunner();
$scenarioRunner->setConfigurationFile(__DIR__.'/example/config/config-test.yaml');
$scenarioRunner->enableTestMode();
// $scenarioRunner->setColumnsToLog(['url']);
$scenarioRunner->run('youtube', true);
