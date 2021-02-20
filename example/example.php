#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Automate\AutoMate;

/**
 * First you have to create your configuration file or replace
 * the path in config.yaml example with ABSOLUTE path (for reliability)
 * then write
 * 
 * $configFile = __DIR__.'/config/config.yaml';
 */
$configFile = __DIR__.'/config/config-test.yaml';

$autoMate = new AutoMate($configFile);

// Run a simplest scenario
//$automate->run('simple');

// Run the loop scenario to show how a loop works
//$autoMate->run('loop');

// Run the condition scenario to show how a condition works
$autoMate->run('condition');

// Run the scenario with a specification
//$automate->run('withspec', true, true);