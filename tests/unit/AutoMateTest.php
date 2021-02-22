<?php 

namespace Automate;

use Automate\Configuration\Configuration;
use Automate\Driver\Proxy\HttpProxy;
use PHPUnit\Framework\TestCase;

class AutoMateTest extends TestCase {

    public function testShouldInstanciateAutomate() {
        $automate = new AutoMate(__DIR__.'/files/config-test.yaml');

        // Because AutoMate constructor load the configuration
        $this->assertSame('chrome', Configuration::get('browser.default'));
    }

    public function testShouldSetAProxy() {
        $automate = new AutoMate(__DIR__.'/files/config-test.yaml');

        $this->assertNull($automate->getProxy());
        
        $httpProxy = new HttpProxy('0.0.0.4', 4567);
        $automate->setProxy($httpProxy);

        $this->assertNotNull($automate->getProxy());
        $this->assertSame('0.0.0.4', $automate->getProxy()->getAdresse());
        $this->assertSame(4567, $automate->getProxy()->getPort());
    }
}