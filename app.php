#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Automate\Scenario\ScenarioRunner;

$scenarioRunner = new ScenarioRunner();
$scenarioRunner->setConfigurationFile(__DIR__.'/config/config-jugid.yaml');
$scenarioRunner->enableTestMode();
$scenarioRunner->run('youtube', true);
