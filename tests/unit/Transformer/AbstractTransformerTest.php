<?php

namespace Automate\Scenario\Transformer;

use Automate\Handler\ScenarioVariableHandler;
use PHPUnit\Framework\TestCase;

class AbstractTransformerTest extends TestCase {

    public function testGoTransformerAndGetProperties() {
        $transformer = new GoTransformer();
        $transformer->setStep(['go'=>'http://github.fr']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Go at adresse http://github.fr', strval($transformer));
    }
}