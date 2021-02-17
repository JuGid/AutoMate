<?php 

namespace Automate\Scenario;

use Automate\Configuration\Configuration;
use Automate\Handler\ScenarioVariableHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Scenario\Scenario
 */
class ScenarioTest extends TestCase {
    const SCENARIO_FILE = __DIR__.'/../files/scenario-test.yaml';

    // public function testShouldParseFileAndSetVariablesAndGetValues(){
        
    //     $config = (new Configuration())->load(__DIR__.'/../files/config-test.yaml');

    //     $scenario = new Scenario('scenario-test');
    //     $scenarioAsArray = [
    //         'browser' => 'chrome',
    //         'variables' => [
    //             'foo'=>'bar'
    //         ],
    //         'scenario'=> [
    //             'steps'=> [
    //                 0 =>[
    //                     'go'=> 'https://github.com/JuGid/AutoMate'
    //                 ],
    //                 1 => [
    //                     'cookie'=> [
    //                         'name'=> 'myCookie',
    //                         'value'=> 'myValue'
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ];
            

    //     $this->assertSame('scenario-test', $scenario->getName());
    //     $this->assertSame($scenarioAsArray, $scenario->getScenarioArray());
    //     $this->assertSame('chrome', $scenario->getScenarioBrowser(null));
    // }

    // public function testShouldForeachOnScenarioAndHaveSameKeys() {
    //     $config = (new Configuration())->load(__DIR__.'/../files/config-test.yaml');
    //     //Because it uses a static variable and php unit use one process for all
    //     ScenarioVariableHandler::removeAll();

    //     $scenario = new Scenario('scenario-test');
    //     $stepKeys = ['go', 'cookie'];
    //     $steps = [
    //         0 =>[
    //             'go'=> 'https://github.com/JuGid/AutoMate'
    //         ],
    //         1 => [
    //             'cookie'=> [
    //                 'name'=> 'myCookie',
    //                 'value'=> 'myValue'
    //             ]
    //         ]
    //     ];

    //     $arrayIndex = 0;

    //     foreach($scenario as $step) {
    //         $this->assertSame($stepKeys[$arrayIndex], $scenario->key());
    //         $this->assertSame($steps[$arrayIndex], $step);
    //         $arrayIndex++;
    //     }
    // }


}