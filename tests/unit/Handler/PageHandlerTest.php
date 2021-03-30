<?php

use Automate\Configuration\Configuration;
use Automate\Handler\PageHandler;
use PHPUnit\Framework\TestCase;

class PageHandlerTest extends TestCase
{
    public function testShouldLoadPageHandler()
    {
        Configuration::load(__DIR__.'/../files/config-test.yaml');
        
        PageHandler::load('tests.index', __DIR__.'/../files/pages');

        $this->assertSame(['xpath'=>'//body'], PageHandler::get('body'));
        $this->assertSame(['css'=>'random'], PageHandler::get('random'));
    }
}
