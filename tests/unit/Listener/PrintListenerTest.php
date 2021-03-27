<?php

namespace Automate\Listener;

use Automate\AutoMateEvents;
use Automate\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

class PrintListenerTest extends TestCase
{
    
    /*
    //Must add @before annotation
    public function loadConfiguration() {
        Configuration::reset();
        Configuration::load(__DIR__.'/../files/config-test.yaml');
        //Default value of verbose is VERBOSE_ALL
    }
    */

    public function testShouldSeeIfEventsReturnedAreGood()
    {
        $eventsShouldBe = [
            'core:runner:spec:line',
            'core:runner:spec:end',
            'core:runner:simple:begin',
            'core:runner:simple:end',
            'core:runner:error',
            'core:step:transform:end',
            'core:logic:exception'
        ];

        $printListener = new PrintListener();
        $this->assertSame($printListener->onEvent(), $eventsShouldBe);
    }
}
