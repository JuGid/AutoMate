<?php

namespace Automate\Logs;

use Automate\Configuration\Configuration;
use Automate\Exception\LogException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Automate\Logs\AbstractLogger
 * @covers \Automate\Logs\DefaultLogger
 */
class LoggerTest extends TestCase
{
    const SCENARIO = 'scenario-tests';

    /**
     * @before
     */
    public function loadConfiguration()
    {
        Configuration::load(__DIR__.'/../files/config-test.yaml');
        Configuration::$config_array['logs']['folder'] = __DIR__.'/../files/logs';
    }

    public function testShouldConstructLogger()
    {
        $logger = new DefaultLogger(['url', 'cookie'], self::SCENARIO);

        $this->assertInstanceOf(AbstractLogger::class, $logger);
        $this->assertTrue(file_exists($logger->getFilepath(LogType::LOG_ERRORS)));
        $this->assertTrue(file_exists($logger->getFilepath(LogType::LOG_WINS)));
        $this->assertSame(['url','cookie','message'], Configuration::get('logs.columns'));
    }

    public function testShouldAddMessageGetMessageSeeMessageQueueIsEmpty()
    {
        $logger = new DefaultLogger(['url', 'cookie'], self::SCENARIO);

        $this->assertSame('', $logger->getMessages());

        $logger->addMessage('Hello');
        $logger->addMessage('World');

        $this->assertSame('Hello,World', $logger->getMessages());
        $this->assertSame('', $logger->getMessages());
    }

    public function testShouldLogData()
    {
        $logger = new DefaultLogger(['url', 'cookie'], self::SCENARIO);

        $data = [
            'url'=>'http://localhost',
            'cookie'=> 'myCookie'
        ];

        $logger->log($data, LogType::LOG_WINS);
        $logger->addMessage('A test');
        $logger->log($data, LogType::LOG_ERRORS);

        $file_w = fopen($logger->getFilepath(LogType::LOG_WINS), 'r');
        $file_e = fopen($logger->getFilepath(LogType::LOG_ERRORS), 'r');

        fgetcsv($file_w);
        $this->assertSame(['http://localhost','myCookie','Finished with success.'], fgetcsv($file_w));

        fgetcsv($file_e);
        $this->assertSame(['http://localhost','myCookie','A test'], fgetcsv($file_e));

        fclose($file_w);
        fclose($file_e);
    }

    public function testShouldTryToLogData()
    {
        $this->expectException(LogException::class);
        $this->expectExceptionMessage('AutoMate try to log data that does not exist in spec file');

        $logger = new DefaultLogger(['url', 'cookie'], self::SCENARIO);

        $data = [
            'url'=>'http://localhost',
            'something'=> 'myCookie'
        ];

        $logger->log($data, LogType::LOG_WINS);
    }

    public function testShouldEndLogger()
    {
        $logger = new DefaultLogger(['url', 'cookie'], self::SCENARIO);

        $this->assertTrue($logger->end());
    }
}
