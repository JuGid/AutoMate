#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Automate\AutoMate;
use Automate\Driver\Proxy\HttpProxy;

/**
 * First you have to create your configuration file or replace
 * the path in config.yaml example with ABSOLUTE path (for reliability)
 * then write
 * 
 * $configFile = __DIR__.'/config/config.yaml';
 */
$configFile = __DIR__.'/config/config-test.yaml';

$autoMate = new AutoMate($configFile);

/**
 * You can set a proxy using
 * $autoMate->setHttpProxy(new HttpProxy('193.269.32.1', 4543));
 */
$autoMate->run('simple');

/**
 * 
 * Run the simplest scenario
 * $autoMate->run('simple');
 * 
 * Run the loop scenario to show how a loop works
 * $autoMate->run('loop');
 * 
 * Run the scenario with a specification
 * $autoMate->run('withspec', true, true);
 * 
 * Run the condition scenario to show how a condition works
 * $autoMate->run('condition');
 * 
 */



