<?php 

namespace Automate\Exception;

use PHPUnit\Framework\TestCase;

class ExceptionsTest extends TestCase {

    public function testShouldThrowBrowserException() {
        $this->expectException(BrowserException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Browser exception');

        throw new BrowserException('Browser exception', 0);
    }

    public function testShouldThrowCommandException() {
        $this->expectException(CommandException::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('Command exception');

        throw new CommandException('Command exception', 1);
    }

    public function testShouldThrowConditionException() {
        $this->expectException(ConditionException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Condition exception');

        throw new ConditionException('Condition exception', 2);
    }

    public function testShouldThrowConfigurationException() {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionCode(3);
        $this->expectExceptionMessage('Configuration exception');

        throw new ConfigurationException('Configuration exception', 3);
    }

    public function testShouldThrowDriverException() {
        $this->expectException(DriverException::class);
        $this->expectExceptionCode(4);
        $this->expectExceptionMessage('Driver exception');

        throw new DriverException('Driver exception', 4);
    }

    public function testShouldThrowLogException() {
        $this->expectException(LogException::class);
        $this->expectExceptionCode(5);
        $this->expectExceptionMessage('Log exception');

        throw new LogException('Log exception', 5);
    }

    public function testShouldThrowNotImplementedException() {
        $this->expectException(NotImplementedException::class);
        $this->expectExceptionCode(6);
        $this->expectExceptionMessage('Not implemented exception');

        throw new NotImplementedException('Not implemented exception', 6);
    }

    public function testShouldThrowSpecificationException() {
        $this->expectException(SpecificationException::class);
        $this->expectExceptionCode(7);
        $this->expectExceptionMessage('Specification exception');

        throw new SpecificationException('Specification exception', 7);
    }

    public function testShouldThrowVariableCallException() {
        $this->expectException(VariableCallException::class);
        $this->expectExceptionCode(8);
        $this->expectExceptionMessage('Variable call exception');

        throw new VariableCallException('Variable call exception', 8);
    }

    public function testShouldThrowVariableException() {
        $this->expectException(VariableException::class);
        $this->expectExceptionCode(9);
        $this->expectExceptionMessage('Variable exception');

        throw new VariableException('Variable exception', 9);
    }
}