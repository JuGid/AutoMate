<?php 

namespace Automate\Driver;

use Automate\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Driver\DriverManager
 */
class DriverManagerTest extends TestCase {

    /**
     * @before
     */
    public function loadConfiguration() {
        Configuration::load(__DIR__.'/../files/config-test.yaml');
    }

    public function testShouldGetPropertiesValue() {
        $dm = new DriverManager();

        $this->assertSame('http://localhost:4444', $dm->getServerUrl());
        $this->assertSame('', $dm->getWebdriverFolder());
    }
}