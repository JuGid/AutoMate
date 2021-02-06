#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Automate\Configuration\Configuration;
use Automate\Scenario\ScenarioRunner;
use Automate\Driver\DriverManager;

//$config = new Configuration();

//var_dump($config->getLogFolder());
//var_dump($config->isLogEnable());
//var_dump($config->getBrowsers());

//$driverManager = new DriverManager();
//$driver = $driverManager->getDriver('chrome');

$scenarioRunner = new ScenarioRunner();
$scenarioRunner->run('wikipedia', null);
