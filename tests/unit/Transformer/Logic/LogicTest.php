<?php

namespace Automate\Transformer\Logic;

use Automate\Configuration\Configuration;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use PHPUnit\Framework\TestCase;

class LogicTest extends TestCase
{
    private const CONFIG_FILE = __DIR__.'/../../files/config-test.yaml';

    public function testShouldTestLogicMethods()
    {
        VariableRegistry::set(Scope::WORLD, 'logictest', 'bonjour world');
        VariableRegistry::set(Scope::SPEC, 'logictest', 'bonjour spec');
        VariableRegistry::set(Scope::SCENARIO, 'logictest', 'bonjour scenario');

        $mock = $this->getMockForAbstractClass(Logic::class);

        $this->assertSame('bonjour world', $mock->get('world.logictest'));
        $this->assertSame('bonjour world', $mock->getWorldVariable('logictest'));
        $this->assertSame('bonjour spec', $mock->getSpecVariable('logictest'));
        $this->assertSame('bonjour scenario', $mock->getScenarioVariable('logictest'));
    }
}
