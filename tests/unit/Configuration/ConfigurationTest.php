<?php 

namespace Automate\Configuration;

use Automate\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase {

    public function testShouldLoadConfigurationFileAndgetValues() {
        Configuration::load(__DIR__.'/../files/config-test.yaml');

        $this->assertSame('chrome', Configuration::get('browser.default'));
        $this->assertSame('/../chromedriver', Configuration::get('drivers.chrome.driver'));
        $this->assertSame('/../gueckodriver', Configuration::get('drivers.firefox.driver'));
        $this->assertSame('/../scenario', Configuration::get('scenario.folder'));
        $this->assertSame('/../specs', Configuration::get('specs.folder'));
        $this->assertSame('/../logs', Configuration::get('logs.folder'));
        $this->assertTrue(Configuration::get('logs.enable'));
        $this->assertSame('30', Configuration::get('wait.for'));
        $this->assertSame('250', Configuration::get('wait.every'));
    }

    public function testShouldAskForAnUnknownValueAndThrowException() {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('The value asked foo.bar does not exist');

        //The config is loaded
        Configuration::get('browser.default'); 
        
        //Should throw the Exception
        Configuration::get('foo.bar');
    }

    public function testShouldGetAndSetLogsColumns() {
       $this->assertFalse(Configuration::hasLogsExceptions());

       Configuration::logsColumns(['url', 'logs']);

       $this->assertTrue(Configuration::hasLogsExceptions());

       $this->assertSame(['url','logs'], Configuration::get('logs.columns'));
    }
}