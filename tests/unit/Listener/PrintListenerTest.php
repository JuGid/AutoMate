<?php

namespace Automate\Listener;

use PHPUnit\Framework\TestCase;

class PrintListenerTest extends TestCase {

    public function testShouldSeeIfEventsReturnedAreGood() {
        $eventsShouldBe = [
            'core:runner:spec:line',
            'core:runner:spec:end',
            'core:runner:simple:begin',
            'core:runner:simple:end',
            'core:runner:error',
            'core:step:transform:end'
        ];

        $printListener = new PrintListener();
        $this->assertSame($printListener->onEvent(), $eventsShouldBe);
    }

    
}