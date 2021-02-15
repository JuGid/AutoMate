<?php 

namespace Automate\Handler;

use Automate\Exception\VariableException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Handler\SpecVariableHandler
 */
class SpecVariableHandlerTest extends TestCase {

    public function testShouldAddAndGetVariables() {
        SpecVariableHandler::add('foo', 'bar');
        SpecVariableHandler::add('bar', 'foo');
        $this->assertSame('bar', SpecVariableHandler::get('foo'));
        $this->assertSame('foo', SpecVariableHandler::get('bar'));
    }

    public function testShouldCallAVariableThatDoesNotExist() {
        $this->expectException(VariableException::class);

        SpecVariableHandler::get('notexist');
    }

    public function testShouldRemoveVariables() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable bar does not exist');

        SpecVariableHandler::remove('bar');
        SpecVariableHandler::get('bar');
    }

    public function testShouldRemoveAllVariables() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable foo does not exist');
        
        SpecVariableHandler::removeAll();
        SpecVariableHandler::get('foo');
    }

    public function testShouldRemoveVariableThatDoesNotExist() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable foofoo cannot be removed as it does not exist');

        SpecVariableHandler::remove('foofoo');
    }

    public function testShouldLoadDataAndGetThem() {
        SpecVariableHandler::removeAll();

        SpecVariableHandler::load(['data'=>'value', 'groot'=>'chris']);
        
        $this->assertSame('value', SpecVariableHandler::get('data'));
        $this->assertSame('chris', SpecVariableHandler::get('groot'));
    }

    public function testShouldSeeIfIsEmpty() {
        SpecVariableHandler::removeAll();

        $this->assertSame(true, SpecVariableHandler::isEmpty());
    }
}