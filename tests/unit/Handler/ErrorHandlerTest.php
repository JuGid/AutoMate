<?php 

namespace Automate\Handler;

use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase {

    public function testShouldInstanciateAndGetProperties() {
        $errorHandler = new ErrorHandler();

        $this->assertSame(0, $errorHandler->countWins());
        $this->assertSame(0, $errorHandler->countErrors());
        $this->assertSame(0, $errorHandler->countErrorsType());
    }

    public function testShouldInstanciateSetWinsAndGetWins() {
        $errorHandler = new ErrorHandler();
        $winsCount = 10;
        for($i=0; $i < $winsCount; $i++) {
            $errorHandler->win();
        }

        $this->assertSame($winsCount, $errorHandler->countWins());
    }

    public function testShouldInstanciateSetErrorAndGetErrors() {
        $errorHandler = new ErrorHandler();
        $errorsCount = 10;
        $typeOfErrorsCount = 2;

        for($i=0; $i < $errorsCount; $i++) {
            $errorHandler->error(['test'=>'errors'], 'Test with error first type');
        }

        for($i=0; $i < $errorsCount; $i++) {
            $errorHandler->error(['test'=>'errors'], 'Test with error second type');
        }

        $this->assertSame($errorsCount*$typeOfErrorsCount, $errorHandler->countErrors());
        $this->assertSame($typeOfErrorsCount, $errorHandler->countErrorsType());
    }

    public function testShouldUseToStringAndGetTheGreenColor() {
        $errorHandler = new ErrorHandler();
        $errorHandler->win();

        $this->assertSame('Scenario with specification finished with Wins : 1 and Errors : 0', strval($errorHandler));
        $this->assertSame('green', $errorHandler->getBackgroundColor());
    }

    public function testShouldUseToStringAndGetTheRedColor() {
        $errorHandler = new ErrorHandler();
        $errorHandler->error(['test'=>'test'], 'My message');

        $this->assertSame('Scenario with specification finished with Wins : 0 and Errors : 1', strval($errorHandler));
        $this->assertSame('red', $errorHandler->getBackgroundColor());
    }
}