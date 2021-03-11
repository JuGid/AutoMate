<?php

namespace Automate\Handler;

use PHPUnit\Framework\TestCase;

class WindowHandlerTest extends TestCase
{
    public function testShouldSetWindowsAndGetIt()
    {
        WindowHandler::setWindows(['win1', 'win2', 'win3']);

        $this->assertSame(['win1', 'win2', 'win3'], WindowHandler::getWindows());
    }

    public function testShouldRemoveAll()
    {
        WindowHandler::removeAll();
        $this->assertSame([], WindowHandler::getWindows());
        
        WindowHandler::setWindows(['win1', 'win2', 'win3']);
        WindowHandler::removeAll();
        $this->assertSame([], WindowHandler::getWindows());
    }

    public function testShouldTestTheQueue()
    {
        WindowHandler::removeAll();
        WindowHandler::addPreviousWindow('win1');
        WindowHandler::addPreviousWindow('win2');
        WindowHandler::addPreviousWindow('win3');

        $this->assertSame('win3', WindowHandler::getPreviousWindow());
        $this->assertSame('win2', WindowHandler::getPreviousWindow());
        $this->assertSame('win1', WindowHandler::getPreviousWindow());
        $this->assertSame('win1', WindowHandler::getPreviousWindow());
    }
}
