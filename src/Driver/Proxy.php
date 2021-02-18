<?php 

namespace Automate\Driver;

use Facebook\WebDriver\Remote\WebDriverCapabilityType;

/**
 * Not implemented yet.
 * @todo User proxy
 */
class Proxy {

    /**
     * @var string
     */
    private $type = 'manual';

    /**
     * http proxy adress
     * @var string
     */
    private $http = '';

    /**
     * Ssl proxy adress
     * @var string
     */
    private $ssl = '';

    /**
     * Ftp proxy adress
     * @var string
     */
    private $ftp = '';

    public function __construct() {}
    
    public function setHttpProxy(string $url, string $port = '') {
        $this->http = sprintf('%s:%s', $url, $port);
    }

    public function setSslProxy(string $url, string $port = '') {
        $this->ssl = sprintf('%s:%s', $url, $port);
    }

    public function setFtpProxy(string $url, string $port = '') {
        $this->ftp = sprintf('%s:%s', $url, $port);
    }

    public function getHttpProxy() {
        return $this->http;
    }

    public function getSslProxy() {
        return $this->ssl;
    }

    public function getFtpProxy() {
        return $this->ftp;
    }

    /**
     * @return array Array of capability proxy
     */
    public function castToProxyCapability() {
        return [
            WebDriverCapabilityType::PROXY => [
                'proxyType' => $this->type,
                'httpProxy' => $this->http,
                'sslProxy' => $this->ssl,
                'ftpProxy' => $this->ftp
                ]
            ];
    }
}