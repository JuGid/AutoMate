#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Automate\Scenario\ScenarioRunner;

$scenarioRunner = new ScenarioRunner();

/**
 * By default, the config file is in %this_lib_dir%/config/config.yaml
 * You can pass a new normed file (see doc) like this :
 * $scenarioRunner->setConfigurationFile(__DIR__.'/config/config.yaml');
 */

 
$scenarioRunner->setConfigurationFile(__DIR__.'/config/config-jugid.yaml');
$scenarioRunner->run('youtube', null);
