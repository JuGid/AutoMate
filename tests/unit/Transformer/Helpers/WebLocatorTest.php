<?php

namespace Automate\Transformer\Helpers;

use Automate\Exception\CommandException;
use PHPUnit\Framework\TestCase;

class WebLocatorTest extends TestCase
{
    public function testShouldTestAllWebLocatorsThenThrowError()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Find an element with error is not allowed');

        $locatorsType = ["css", "xpath","id","class","name","tag","linktext","pltext","error"];
        $locatorTypeExpected = ["css selector","xpath","id","class name","name","tag name","link text","partial link text", "error should be thrown"];
        for ($i = 0; $i < count($locatorsType); $i++) {
            $loc = WebLocator::get($locatorsType[$i], "element");
            $this->assertSame($locatorTypeExpected[$i], $loc->getMechanism());
            $this->assertSame('element', $loc->getValue());
        }
    }
}
