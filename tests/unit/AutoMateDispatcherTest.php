<?php

namespace Automate;

use Automate\Transformer\GoTransformer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AutoMateDispatcherTest extends TestCase
{
    public function testShouldInstanciateDispatcher()
    {
        $dispatcher = new AutoMateDispatcher();

        $result = $dispatcher->notify(AutoMateEvents::RUNNER_SIMPLE_BEGIN, []);

        $this->assertEquals(0, $dispatcher->countListeners());
        $this->assertFalse($result);
    }

    public function testShouldAttachListener()
    {
        $dispatcher = new AutoMateDispatcher();
        $dispatcher->attach(new GoTransformer());
        $this->assertEquals(1, $dispatcher->countListeners());

        $dispatcher->attach(new GoTransformer());
        $this->assertEquals(2, $dispatcher->countListeners());
    }
}
