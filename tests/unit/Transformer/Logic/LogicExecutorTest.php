<?php

namespace Automate\Transformer\Logic;

use Automate\AutoMateDispatcher;
use Automate\Configuration\Configuration;

use PHPUnit\Framework\TestCase;

class LogicExecutorTest extends TestCase
{
    private const CONFIG_FILE = __DIR__.'/../../files/config-test.yaml';

    public function testShouldTestLogicExecutorMethods()
    {
        include_once(__DIR__.'/MyLogic.php');
        include_once(__DIR__.'/MyLogicWithException.php');

        Configuration::load(self::CONFIG_FILE);

        $dispatcher = new AutoMateDispatcher();
        $executor = new LogicExecutor();

        $executor->setEventDispatcher($dispatcher);

        $this->assertSame(Configuration::get('logics.valueAtException'), $executor->getAnswser());
        $this->assertTrue($executor->for('personnal.MyLogic')->execute()->getAnswser());
        $this->assertFalse($executor->for('MyLogicWithException')->execute()->getAnswser());

    }
}
