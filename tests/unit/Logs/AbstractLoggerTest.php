<?php 

namespace Automate\Logs;

use Automate\Exception\LogException;
use PHPUnit\Framework\TestCase;
/**
 * @covers \Automate\Logs\AbstractLogger
 */
class AbstractLoggerTest extends TestCase {

    public function testShouldCreateSetAndGetOnlyOnAbstractLogger(){
        // Please do not use init function
        $logger = new DefaultLogger('/logs');

        $this->assertSame(true, $logger->isEnable());
        
        $logger->disable();

        $this->assertSame(false, $logger->isEnable());
        $this->assertSame('', $logger->getMessages());
        $this->assertInstanceOf(LoggerConfiguration::class, $logger->getConfiguration());
    }

    public function testShouldAddMessagesAndGetThemToWatchMessageQueue() {
        $logger = new DefaultLogger('/logs');
        $messageArray = ['myMessage', 'newMessage'];

        $logger->addMessage($messageArray[0]);
        $logger->addMessage($messageArray[1]);

        $this->assertSame(implode(',', $messageArray), $logger->getMessages());
        $this->assertSame('', $logger->getMessages());

    }

    public function testShouldAddANewLoggerConfiguration() {
        $config = new LoggerConfiguration();
        $config->setLogsDirectory('/logs');

        $logger = new DefaultLogger('/foo');
        $this->assertSame('/foo', $logger->getConfiguration()->getLogsDirectory());
        $logger->setConfiguration($config);
        $this->assertSame('/logs', $logger->getConfiguration()->getLogsDirectory());
    }

    public function testShouldEndWithException() {
        $this->expectException(LogException::class);
        $this->expectExceptionMessage('Use init() to set logs files');

        $logger = new DefaultLogger('/logs');
        $logger->end();
    }
}