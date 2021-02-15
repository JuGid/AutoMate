<?php 

namespace Automate\Handler;

use Automate\Exception\VariableException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Handler\ScenarioVariableHandler
 */
class ScenarioVariableHandlerTest extends TestCase {

    public function testShouldAddAndGetVariables() {
        ScenarioVariableHandler::add('foo', 'bar');
        ScenarioVariableHandler::add('bar', 'foo');
        $this->assertSame('bar', ScenarioVariableHandler::get('foo'));
        $this->assertSame('foo', ScenarioVariableHandler::get('bar'));
    }

    public function testShouldCallAVariableThatDoesNotExist() {
        $this->expectException(VariableException::class);

        ScenarioVariableHandler::get('notexist');
    }

    public function testShouldRemoveVariables() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable bar does not exist');

        ScenarioVariableHandler::remove('bar');
        ScenarioVariableHandler::get('bar');
    }

    public function testShouldRemoveAllVariables() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable foo does not exist');
        
        ScenarioVariableHandler::removeAll();
        ScenarioVariableHandler::get('foo');
    }

    public function testShouldRemoveVariableThatDoesNotExist() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable foofoo cannot be removed as it does not exist');

        ScenarioVariableHandler::remove('foofoo');
    }

    public function testShouldSeeIfIsEmpty() {
        ScenarioVariableHandler::removeAll();

        $this->assertSame(true, ScenarioVariableHandler::isEmpty());
    }
}