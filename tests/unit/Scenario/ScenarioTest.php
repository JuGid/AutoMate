<?php

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Exception\ScenarioException;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * @covers \Automate\Scenario\Scenario
 */
class ScenarioTest extends TestCase
{
    private const SCENARIO = 'scenario-tests';
    private const SCENARIO_NO_BROWSER = 'scenario-no-browser';
    private const SCENARIO_NO_STEPS =  'scenario-no-steps';
    private const SCENARIO_PARSE_ERROR = 'scenario-parse-error';
    private const SCENARIO_ARRAY = [
        'browser'=> 'chrome',
        'variables' => [
            'name'=> 'bonjour',
            'cookie'=> 'nouveau'
        ],
        'scenario'=>[
            'steps'=> [
                0 =>[
                    'go'=>'{{ spec.url }}'
                ],
                1 =>[
                    'cookie'=> [
                        'name'=> "{{ scenario.name }}",
                        'value'=> "{{ scenario.cookie }}"
                    ]
                ]
            ]
        ]
    ];

    /**
     * @before
     */
    public function setConfiguration()
    {
        Configuration::load(__DIR__.'/../files/config-test.yaml');
        Configuration::$config_array['scenario']['folder'] = __DIR__.'/../files/scenario';
        VariableRegistry::reset(Scope::SCENARIO);
    }

    public function testShouldCreateNewScenarioObject()
    {
        $scenario = new Scenario(self::SCENARIO);
        $this->assertSame(self::SCENARIO, $scenario->getName());
        $this->assertSame(self::SCENARIO_ARRAY, $scenario->getScenarioArray());
        $this->assertSame(['go'=>'{{ spec.url }}'], $scenario->current());
    }

    public function testShouldTryEverySteps()
    {
        $scenario = new Scenario(self::SCENARIO);

        $steps = self::SCENARIO_ARRAY['scenario']['steps'];
        for ($i = 0; $i < count($steps); $i++) {
            $this->assertSame($steps[$i], $scenario->current());
            $scenario->next();
        }

        $this->assertFalse($scenario->valid());
        $scenario->rewind();
        $this->assertSame(self::SCENARIO_ARRAY['scenario']['steps'][0], $scenario->current());
        $this->assertSame('go', $scenario->key());
    }

    public function testShouldGetTheScenarioBrowser()
    {
        $scenario = new Scenario(self::SCENARIO);

        $this->assertSame('chrome', $scenario->getScenarioBrowser('chrome'));
        $this->assertSame('chrome', $scenario->getScenarioBrowser('firefox'));
        $this->assertSame('chrome', $scenario->getScenarioBrowser(''));
        $this->assertSame('chrome', $scenario->getScenarioBrowser(null));
    }

    public function testShouldGetTheScenarioBrowserButScenarioBrowserNull()
    {
        $scenario = new Scenario(self::SCENARIO_NO_BROWSER);
        Configuration::$config_array['browser']['default'] = 'firefox';

        $this->assertSame('firefox', $scenario->getScenarioBrowser(null));
        $this->assertSame('firefox', $scenario->getScenarioBrowser(''));
        $this->assertSame('chrome', $scenario->getScenarioBrowser('chrome'));
    }

    public function testShouldGetScenarioVariables()
    {
        $scenario = new Scenario(self::SCENARIO);
        
        $this->assertSame('bonjour', VariableRegistry::get(Scope::SCENARIO, 'name'));
        $this->assertSame('nouveau', VariableRegistry::get(Scope::SCENARIO, 'cookie'));
    }

    public function testShouldThrowNoStepException()
    {
        $this->expectException(ScenarioException::class);
        $this->expectExceptionMessage('You must define steps in your scenario file');

        $scenario = new Scenario(self::SCENARIO_NO_STEPS);
    }

    public function testShouldThrowParseException()
    {
        $this->expectException(ParseException::class);

        $scenario = new Scenario(self::SCENARIO_PARSE_ERROR);
    }
}
