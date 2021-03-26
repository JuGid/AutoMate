<?php

namespace Automate\Handler;

use Automate\AutoMateError;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function testShouldInstanciateAndGetProperties()
    {
        $errorHandler = new ErrorHandler();

        $this->assertSame(0, $errorHandler->countWins());
        $this->assertSame(0, $errorHandler->countErrors());
        $this->assertFalse($errorHandler->getShouldStoreDataset());
    }

    public function testShouldInstanciateAndWin()
    {
        $errorHandler = new ErrorHandler();

        $wins = 5;
        for ($i = 0; $i<$wins; $i++) {
            $errorHandler->win();
        }

        $this->assertSame(5, $errorHandler->countWins());
        $this->assertSame('green', $errorHandler->getBackgroundColor());
        $this->assertSame('Scenario with specification finished with Wins : 5 and Errors : 0', (string) $errorHandler);
    }

    public function testShouldInstanciateAndErrorAndShouldNotStoreDataset()
    {
        $errorHandler = new ErrorHandler();

        $errors = 5;
        for ($i = 0; $i<$errors; $i++) {
            $errorHandler->error('type', 'Exception', ['one', 'two']);
        }

        $this->assertSame(5, $errorHandler->countErrors());
        $this->assertSame(1, $errorHandler->countErrorsType());
        $this->assertSame('red', $errorHandler->getBackgroundColor());
        $this->assertSame('Scenario with specification finished with Wins : 0 and Errors : 5', (string) $errorHandler);
    }

    public function testShouldInstanciateAndErrorAndShouldStoreDataset()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->shouldStoreDataset();
        $this->assertTrue($errorHandler->getShouldStoreDataset());

        $errors = 5;
        for ($i = 0; $i<$errors; $i++) {
            $errorHandler->error('type', 'AutoMate\Exception\TimeoutException', ['one', 'two']);
        }

        $errorsFromHandler = $errorHandler->getErrors();
        $this->assertSame('one,two', reset($errorsFromHandler)->getDatasetAsString());
        $this->assertSame(['one','two'], reset($errorsFromHandler)->getDataset());
        $this->assertSame(5, $errorHandler->countErrors());
        $this->assertSame(1, $errorHandler->countErrorsType());
        $this->assertSame('red', $errorHandler->getBackgroundColor());
    }

    public function testShouldCompareErrors()
    {
        $error1 = new AutoMateError('aaa', 'Exception', []);
        $error2 = new AutoMateError('bbb', 'Exception', []);
        $error3 = new AutoMateError('aaa', 'Exception', []);

        $this->assertEquals(-1, AutoMateError::compare($error1, $error2));
        $this->assertEquals(1, AutoMateError::compare($error2, $error1));
        $this->assertEquals(0, AutoMateError::compare($error1, $error3));
    }
}
