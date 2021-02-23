#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Automate\AutoMate;
use Automate\Driver\DriverConfiguration;
use Automate\Driver\Proxy\HttpProxy;
use Facebook\WebDriver\Firefox\FirefoxProfile;

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
 * This configuration part is optionnal and helps you to customize AutoMate
 * 
 * For Firefox Profile 
 * @see https://github.com/php-webdriver/php-webdriver/wiki/FirefoxProfile
 * 
 * $driverConfiguration = new DriverConfiguration();
 * $driverConfiguration->setHttpProxy('0.0.0.0', 1234);
 * $driverConfiguration->setFirefoxProfile(new FirefoxProfile());
 * $driverConfiguration->setServerUrl('http://localhost:4444');
 * 
 * $autoMate->setDriverConfiguration($driverConfiguration)
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



