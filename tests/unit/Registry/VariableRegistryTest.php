<?php

namespace Automate\Registry;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VariableRegistryTest extends TestCase
{
    public function testShouldAddVariableAndGetIt()
    {
        VariableRegistry::set(Scope::WORLD, 'test', 'world');
        VariableRegistry::set(Scope::SPEC, 'test', 'spec');
        VariableRegistry::set(Scope::SCENARIO, 'test', 'scenario');

        $this->assertSame('world', VariableRegistry::get(Scope::WORLD, 'test'));
        $this->assertSame('spec', VariableRegistry::get(Scope::SPEC, 'test'));
        $this->assertSame('scenario', VariableRegistry::get(Scope::SCENARIO, 'test'));
    }

    public function testShouldRemoveAllAndTestIsEmpty()
    {
        $this->assertFalse(VariableRegistry::isEmpty(Scope::WORLD));

        VariableRegistry::reset(Scope::WORLD);

        $this->assertTrue(VariableRegistry::isEmpty(Scope::WORLD));
    }

    public function testShouldSetMultiple()
    {
        $data = [
            'url'=>'http://github.com',
            'cookie'=>'cookiename'
        ];

        VariableRegistry::setMultiple(Scope::SCENARIO, $data);

        $this->assertSame('scenario', VariableRegistry::get(Scope::SCENARIO, 'test'));
        $this->assertSame('http://github.com', VariableRegistry::get(Scope::SCENARIO, 'url'));
        $this->assertSame('cookiename', VariableRegistry::get(Scope::SCENARIO, 'cookie'));
    }

    public function testShouldSetWithUnknownScope()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(VariableRegistry::INVALID_SCOPE);

        VariableRegistry::set('unknown', 'notworking', 'value');
    }

    public function testShouldGetWithUnknownScope()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(VariableRegistry::INVALID_SCOPE);

        VariableRegistry::get('unknown', 'notworking');
    }

    public function testShouldSetMultipleWithUnknownScope()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(VariableRegistry::INVALID_SCOPE);

        VariableRegistry::setMultiple('unknown', ['url'=>'http://github.fr', 'cookie'=>'cookiename']);
    }

    public function testShouldResetWithUnknownScope()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(VariableRegistry::INVALID_SCOPE);

        VariableRegistry::reset('unknown');
    }

    public function testShouldIsEmptyWithUnknownScope()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(VariableRegistry::INVALID_SCOPE);

        VariableRegistry::isEmpty('unknown');
    }

    public function testShouldGetAnUnknowVariableInKnownScope()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(VariableRegistry::INVALID_SCOPE);

        VariableRegistry::get(Scope::SCENARIO, 'myVariable');
    }
}
