<?php 

namespace Automate\Driver\Proxy;

use PHPUnit\Framework\TestCase;

class HttpProxyTest extends TestCase {


    public function testShouldSetAndGetAProxy() {
        $proxyCapa = ['proxyType' => 'manual', 'httpProxy'=>sprintf('%s:%s', '0.0.0.0', 4567)];

        $httpProxy = new HttpProxy('0.0.0.0', 4567);

        $this->assertSame('0.0.0.0', $httpProxy->getAdresse());
        $this->assertSame(4567, $httpProxy->getPort());
        $this->assertSame('manual', $httpProxy->getType());
        $this->assertSame($proxyCapa, $httpProxy->getAsCapability());

    }
}