<?php 

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Handler\ScenarioVariableHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Scenario\Scenario
 */
class ScenarioTest extends TestCase {
    const SCENARIO = 'scenario-tests';
    const SCENARIO_NO_BROWSER = 'scenario-no-browser';
    const SCENARIO_ARRAY = [
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
    public function setConfiguration() {
        Configuration::load(__DIR__.'/../files/config-test.yaml');
        Configuration::$config_array['scenario']['folder'] = __DIR__.'/../files/scenario';
        ScenarioVariableHandler::removeAll();
    }

    public function testShouldCreateNewScenarioObject() {
        $scenario = new Scenario(self::SCENARIO);
        $this->assertSame(self::SCENARIO, $scenario->getName());
        $this->assertSame(self::SCENARIO_ARRAY, $scenario->getScenarioArray());
        $this->assertSame(['go'=>'{{ spec.url }}'], $scenario->current());
    }

    public function testShouldTryEverySteps() {
        $scenario = new Scenario(self::SCENARIO);

        $steps = self::SCENARIO_ARRAY['scenario']['steps'];
        for($i = 0; $i < count($steps); $i++) {
            $this->assertSame($steps[$i], $scenario->current());
            $scenario->next();
        }
    }

    public function testShouldGetTheScenarioBrowser() {
        $scenario = new Scenario(self::SCENARIO);

        $this->assertSame('chrome', $scenario->getScenarioBrowser('chrome'));
        $this->assertSame('chrome', $scenario->getScenarioBrowser('firefox'));
        $this->assertSame('chrome', $scenario->getScenarioBrowser(''));
        $this->assertSame('chrome', $scenario->getScenarioBrowser(null));

    }

    public function testShouldGetTheScenarioBrowserButScenarioBrowserNull() {
        $scenario = new Scenario(self::SCENARIO_NO_BROWSER);
        Configuration::$config_array['browser']['default'] = 'firefox';

        $this->assertSame('firefox', $scenario->getScenarioBrowser(null));
        $this->assertSame('firefox', $scenario->getScenarioBrowser(''));
        $this->assertSame('chrome', $scenario->getScenarioBrowser('chrome'));
    }

    public function testShouldGetScenarioVariables() {
        $scenario = new Scenario(self::SCENARIO);
        
        $this->assertSame('bonjour', ScenarioVariableHandler::get('name'));
        $this->assertSame('nouveau', ScenarioVariableHandler::get('cookie'));
    }

}