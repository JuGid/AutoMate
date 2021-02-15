<?php 

namespace Automate\Handler;

use Automate\Exception\VariableException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Handler\GlobalVariableHandler
 */
class GlobalVariableHandlerTest extends TestCase {

    public function testShouldAddAndGetVariables() {
        GlobalVariableHandler::add('foo', 'bar');
        GlobalVariableHandler::add('bar', 'foo');
        $this->assertSame('bar', GlobalVariableHandler::get('foo'));
        $this->assertSame('foo', GlobalVariableHandler::get('bar'));
    }

    public function testShouldCallAVariableThatDoesNotExist() {
        $this->expectException(VariableException::class);

        GlobalVariableHandler::get('notexist');
    }

    public function testShouldRemoveVariables() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable bar does not exist');

        GlobalVariableHandler::remove('bar');
        GlobalVariableHandler::get('bar');
    }

    public function testShouldRemoveAllVariables() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable foo does not exist');
        
        GlobalVariableHandler::removeAll();
        GlobalVariableHandler::get('foo');
    }

    public function testShouldRemoveVariableThatDoesNotExist() {
        $this->expectException(VariableException::class);
        $this->expectExceptionMessage('Variable foofoo cannot be removed as it does not exist');

        GlobalVariableHandler::remove('foofoo');
    }

    public function testShouldSeeIfIsEmpty() {
        GlobalVariableHandler::removeAll();

        $this->assertSame(true, GlobalVariableHandler::isEmpty());
    }

}