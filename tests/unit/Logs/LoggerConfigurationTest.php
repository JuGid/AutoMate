<?php 

namespace Automate\Logs;

use Automate\Handler\GlobalVariableHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Logs\LoggerConfiguration
 */
class LoggerConfigurationTest extends TestCase {

    public function testShouldCreateSetAndGetValue() {
        $loggerConfiguration = new LoggerConfiguration();
        GlobalVariableHandler::setScenarioName('scenario-test');
        
        $loggerConfiguration->setlogColumns(['foo', 'bar', 'action']);
        $loggerConfiguration->setLogsDirectory('/logs');

        $this->assertFalse($loggerConfiguration->getPartialName() == uniqid(), 'Test unique partial name id');
        $this->assertSame(['foo', 'bar', 'action'], $loggerConfiguration->getLogColumns());
        $this->assertSame('/logs', $loggerConfiguration->getLogsDirectory());

        $this->assertSame(
            '/logs/scenario-test/LOGS_WINS_'.$loggerConfiguration->getPartialName() . '.csv', 
            $loggerConfiguration->getFilepathLogWins()
        );

        $this->assertSame(
            '/logs/scenario-test/LOGS_ERRORS_'.$loggerConfiguration->getPartialName() . '.csv', 
            $loggerConfiguration->getFilepathLogErrors()
        );

    }
}