#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Automate\Scenario\AutoMate;

$configFile = __DIR__.'/example/config/config-test.yaml';

$autoMate = new AutoMate($configFile);
$autoMate->run('youtube', true, true);
//$automate->run('internet');
