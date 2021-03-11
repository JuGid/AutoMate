<?php

namespace Automate;

use Automate\Configuration\Configuration;
use Automate\Driver\DriverConfiguration;
use Automate\Transformer\GoTransformer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AutoMateTest extends TestCase
{
    public function testShouldInstanciateAutomate()
    {
        $automate = new AutoMate(__DIR__.'/files/config-test.yaml');

        // Because AutoMate constructor load the configuration
        $this->assertSame('chrome', Configuration::get('browser.default'));
    }

    public function testShouldSetADriverConfiguration()
    {
        $automate = new AutoMate(__DIR__.'/files/config-test.yaml');

        $this->assertNull($automate->getDriverConfiguration());
        
        $driverConfiguration = new DriverConfiguration();
        $driverConfiguration->setHttpProxy('0.0.0.1', 4567);

        $automate->setDriverConfiguration($driverConfiguration);

        $this->assertNotNull($automate->getDriverConfiguration()->getHttpProxy());
        $this->assertSame('http://localhost:4444', $automate->getDriverConfiguration()->getServerUrl());
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldResgisterANewPlugin()
    {
        $automate = new AutoMate(__DIR__.'/files/config-test.yaml');

        $automate->registerPlugin(new GoTransformer());
    }
}
