<?php 

namespace Automate\Driver;

use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use PHPUnit\Framework\TestCase;

class ProxyTest extends TestCase {


    public function testShouldSetAndGetAProxy() {
        $proxyCapa = [
            WebDriverCapabilityType::PROXY => [
                'proxyType' => 'manual',
                'httpProxy' => '0.0.0.1:80',
                'sslProxy' => '0.0.0.3:443',
                'ftpProxy' => '0.0.0.2:23'
            ]
        ];

        $proxy = new Proxy();

        $proxy->setHttpProxy('0.0.0.1', '80');
        $proxy->setFtpProxy('0.0.0.2', '23');
        $proxy->setSslProxy('0.0.0.3','443');

        $this->assertSame('0.0.0.1:80', $proxy->getHttpProxy());
        $this->assertSame('0.0.0.2:23', $proxy->getFtpProxy());
        $this->assertSame('0.0.0.3:443', $proxy->getSslProxy());
        $this->assertSame($proxyCapa, $proxy->castToProxyCapability());
    }
}