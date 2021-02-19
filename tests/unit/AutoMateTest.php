<?php 

namespace Automate;

use Automate\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

class AutoMateTest extends TestCase {

    public function testShouldInstanciateAutomate() {
        $automate = new AutoMate(__DIR__.'/files/config-test.yaml');

        $this->assertSame('chrome', Configuration::get('browser.default'));
    }
}