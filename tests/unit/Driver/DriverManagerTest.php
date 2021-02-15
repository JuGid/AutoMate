<?php 

namespace Automate\Driver;

use Automate\Exception\BrowserException;
use Automate\Exception\ConfigurationException;
use Automate\Exception\DriverException;
use PHPUnit\Framework\TestCase;

class DriverManagerTest extends TestCase {

    public function testShouldCreateDriverAndGetProperties() {
        $driverManager = new DriverManager();

        $this->assertNull($driverManager->getCurrentDriver());
        $this->assertSame('', $driverManager->getWebdriverFolder());
    }

    public function testGetNotImplementedFirefoxDriver() {
        $this->expectException(DriverException::class);
        $this->expectExceptionMessage('Not implemented driver');
        
        $driverManager = new DriverManager();

        $driver = $driverManager->getDriver('firefox', '/');
    }

    public function testGetNotImplementedSafariDriver() {
        $this->expectException(DriverException::class);
        $this->expectExceptionMessage('Not implemented driver');
   
        $driverManager = new DriverManager();

        $driver = $driverManager->getDriver('safari', '/');
    }

    public function testGetNotImplementedOtherDriver() {
        $this->expectException(BrowserException::class);
        $this->expectExceptionMessage('The browser foo is not managed by AutoMate');
        
        $driverManager = new DriverManager();

        $driver = $driverManager->getDriver('foo', '/');
    }

    public function testWithEmptyWebdriverFolder() {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Webdriver folder is not valid');

        $driverManager = new DriverManager();

        $driverManager->getDriver('firefox', '');
    }
}