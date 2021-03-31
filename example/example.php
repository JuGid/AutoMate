#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Automate\AutoMate;
use Automate\AutoMateEvents;
use Automate\Driver\DriverConfiguration;
use Automate\Driver\Proxy\HttpProxy;
use Facebook\WebDriver\Firefox\FirefoxProfile;

$autoMate = new AutoMate(__DIR__.'/config/config-test.yaml');

/**
 * This configuration part is optionnal and helps you to customize AutoMate
 * 
 * For Firefox Profile 
 * @see https://github.com/php-webdriver/php-webdriver/wiki/FirefoxProfile
 * 
 * $driverConfiguration = new DriverConfiguration();
 * @see https://github.com/JuGid/AutoMate/wiki/Driver-configuration
 * 
 * You can also register a plugin to use it on some AutoMate events
 * For example, to register your custom Transformer
 * 
 * $autoMate->registerPlugin(new MyTransformer());
 */
 
$autoMate->run('simple', false, true);

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



