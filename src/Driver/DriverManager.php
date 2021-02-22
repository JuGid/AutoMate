<?php

namespace Automate\Driver;

use Automate\Configuration\Configuration;
use Automate\Console\Console;
use Automate\Driver\Proxy\HttpProxy;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Exception\BrowserException;
use Automate\Exception\ConfigurationException;
use Automate\Handler\WindowHandler;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

/**
 * This class is used to get the good Webdriver and process the start of it.
 */
class DriverManager {

  /**
   * @var string
   */
  private $webdriverPath = '';

  /**
   * @var string
   */
  private $serverUrl = 'http://localhost:4444';

  public function __construct(){}

  /**
   * @codeCoverageIgnore
   * 
   * @param string $browser The browser which you want the driver
   * @param HttpProxy $httpProxy The proxy
   * @return RemoteWebDriver|null 
   */
  public function getDriver(string $browser, HttpProxy $httpProxy = null) {
    try {
      $this->webdriverPath = Configuration::get('drivers.'.$browser.'.driver');
    } catch( ConfigurationException $e) {
      throw new BrowserException('The browser ' . $browser . ' is not managed by php-webdriver');
    }
    
    $driver = null;
    $caps = null;
    if($httpProxy == null && $browser == 'chrome') {
      putenv('WEBDRIVER_CHROME_DRIVER=' . $this->webdriverPath);
      $driver = ChromeDriver::start();
    } else {
      $desired = new DesiredCapabilities();
      if(method_exists($desired, $browser)) {
        $caps = DesiredCapabilities::$browser();
      } else {
        throw new BrowserException('The browser ' . $browser . ' is not managed by php-webdriver');
      }
  
      if($httpProxy !== null) {
        $caps->setCapability(WebDriverCapabilityType::PROXY, $httpProxy->getAsCapability());
      }
  
      $driver = RemoteWebDriver::create($this->serverUrl, $caps);
    }

    WindowHandler::setWindows($driver->getWindowHandles());
    return $driver;
  }

  public function getWebdriverPath() : string {
    return $this->webdriverPath;
  }

  public function getServerUrl() : string {
    return $this->serverUrl;
  }
}
