<?php 

namespace Automate\Driver;

use Automate\Driver\Proxy\HttpProxy;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use PHPUnit\Framework\TestCase;

class DriverConfigurationTest extends TestCase{

    public function testShouldSetAndGetProperties() {
        $dc = new DriverConfiguration();

        $this->assertNull($dc->getHttpProxy());
        $this->assertNull($dc->getFirefoxProfile());
        $this->assertSame('http://localhost:4444', $dc->getServerUrl());

        $dc->setHttpProxy('0.0.0.1', 1234);
        $dc->setServerUrl('http://localhost:3333');
        $dc->setFirefoxProfile(new FirefoxProfile());

        $this->assertInstanceOf(HttpProxy::class, $dc->getHttpProxy());
        $this->assertSame('http://localhost:3333', $dc->getServerUrl());
        $this->assertInstanceOf(FirefoxProfile::class, $dc->getFirefoxProfile());
    }
}