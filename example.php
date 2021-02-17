#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Automate\Scenario\AutoMate;

$configFile = __DIR__.'/example/config/config-test.yaml';

$autoMate = new AutoMate($configFile);

// Can run with a specification in test mode
$autoMate->run('youtube', true, true);

// Can run a simple scenario without specification
//$automate->run('internet');
