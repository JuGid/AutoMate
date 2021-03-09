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
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Event should comes from class AutoMateEvents constants');

        $dispatcher = new AutoMateDispatcher();
        $dispatcher->attach(AutoMateEvents::STEP_TRANSFORM, new GoTransformer());
        $this->assertEquals(1, $dispatcher->countListeners());

        $dispatcher->attach([AutoMateEvents::RUNNER_ENDS_ERROR, AutoMateEvents::RUNNER_ERROR], new GoTransformer());
        $this->assertEquals(3, $dispatcher->countListeners());

        $dispatcher->attach(4, new GoTransformer());
    }
}
