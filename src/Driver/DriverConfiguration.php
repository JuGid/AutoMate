<?php 

namespace Automate\Driver;

use Automate\Driver\Proxy\HttpProxy;
use Facebook\WebDriver\Firefox\FirefoxProfile;

class DriverConfiguration {

    /**
     * @var FirefoxProfile
     */
    private $firefoxProfile = null;

    /**
     * @var HttpProxy
     */
    private $httpProxy = null;

    /**
     * @var string
     */
    private $serverUrl = 'http://localhost:4444';

    public function __construct(){}

    public function setFirefoxProfile(FirefoxProfile $profile) : void {
        $this->firefoxProfile = $profile;
    }

    public function getFirefoxProfile() : ?FirefoxProfile {
        return $this->firefoxProfile;
    }

    public function setHttpProxy(string $adresse, int $port) : void {
        $this->httpProxy = new HttpProxy($adresse, $port);
    }

    public function getHttpProxy() : ?HttpProxy {
        return $this->httpProxy;
    }

    public function setServerUrl(string $url) : void {
        $this->serverUrl = $url;
    }

    public function getServerUrl() : string {
        return $this->serverUrl;
    }
}