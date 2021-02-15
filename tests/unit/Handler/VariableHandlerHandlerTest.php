<?php 

namespace Automate\Handler;

use Automate\Exception\VariableException;
use Automate\Exception\VariableCallException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Handler\VariableHandlerHandler
 */
class VariableHandlerHandlerTest extends TestCase {

    public function testShouldSetAndGetVariablesInDifferentScope() {
        VariableHandlerHandler::set('spec', 'chris', 'hunter');
        VariableHandlerHandler::set('scenario', 'groot', 'tree');
        VariableHandlerHandler::set('global', 'raccoon', 'dog');

        $this->assertSame('hunter', VariableHandlerHandler::get('spec', 'chris'));
        $this->assertSame('tree', VariableHandlerHandler::get('scenario', 'groot'));
        $this->assertSame('dog', VariableHandlerHandler::get('global', 'raccoon'));
    }

    public function testShouldSetFromUnknownScope() {
        $this->expectException(VariableCallException::class);
        $this->expectExceptionMessage('The scope unknown does not exist');

        VariableHandlerHandler::set('unknown', 'foo', 'bar');
    }

    public function testShouldGetFromUnknownScope() {
        $this->expectException(VariableCallException::class);
        $this->expectExceptionMessage('The scope unknown does not exist');

        VariableHandlerHandler::get('unknown', 'foo');
    }

    public function testShouldRemoveFromUnknownScope() {
        $this->expectException(VariableCallException::class);
        $this->expectExceptionMessage('The scope unknown does not exist');

        VariableHandlerHandler::remove('unknown', 'chris');
    }

    public function testShouldGetFromKnownScopeButUnknownVariable() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable automate does not exist');

        VariableHandlerHandler::get('spec', 'automate');
    }

    public function testShouldRemoveFromKnownScopeButUnknownVariable() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable automate cannot be removed as it does not exist');

        VariableHandlerHandler::remove('spec', 'automate');
    }

    public function testShouldSetAnAlreadyExistingVariableFromKnowScope() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable chris already exists');

        VariableHandlerHandler::set('spec', 'chris', 'other');
    }

    public function testShouldRemoveAllFromKnownAndUnknownScopes() {
        $this->expectException(VariableCallException::class);
        $this->expectExceptionMessage('The scope unknown does not exist');
        
        VariableHandlerHandler::removeAll('spec');
        VariableHandlerHandler::removeAll('scenario');
        VariableHandlerHandler::removeAll('global');

        $this->assertSame(true,VariableHandlerHandler::isEmpty('spec'));
        $this->assertSame(true,VariableHandlerHandler::isEmpty('scenario'));
        $this->assertSame(true,VariableHandlerHandler::isEmpty('global'));

        VariableHandlerHandler::removeAll('unknown');
    }

}