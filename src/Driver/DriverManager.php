<?php

namespace Automate\Driver;

use Automate\Configuration\Configuration;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Exception\BrowserException;
use Automate\Exception\ConfigurationException;
use Automate\Handler\WindowHandler;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * This class is used to get the good Webdriver and process the start of it.
 */
class DriverManager {

  /**
   * @var string
   */
  private $webdriverFolder;

  /**
   * @var string
   */
  private $serverUrl;

  public function __construct()
  {
    $this->webdriverFolder = '';
  }

  /**
   * @param string $browser The browser which you want the driver
   * @param string $serverUrl The server url (selenium)
   * @return RemoteWebDriver|null 
   */
  public function getDriver(string $browser, string $serverUrl = 'http://localhost:4444') {
    $this->webdriverFolder = Configuration::get('drivers.'.$browser.'.driver');
    $this->serverUrl = $serverUrl;
    
    if(empty($serverUrl)) {
      throw new BrowserException('Server url is empty. Please provide an valid adresse.');
    }

    $driver = null;
    if($browser == 'chrome') {
      $driver = $this->getChromeDriver();
    }elseif($browser == 'firefox') {
      $driver = $this->getFirefoxDriver();
    }elseif($browser == 'safari') {
      $driver = $this->getSafariDriver();
    }elseif($browser == 'edge') {
      $driver = $this->getEdgeDriver();
    }else {
      throw new BrowserException('The browser ' . $browser . ' is not managed by AutoMate');
    }
    WindowHandler::setWindows($driver->getWindowHandles());
    return $driver;
  }

  public function getWebdriverFolder() : string {
    return $this->webdriverFolder;
  }

  private function getChromeDriver() : ChromeDriver {
    putenv('WEBDRIVER_CHROME_DRIVER=' . $this->webdriverFolder);
    return ChromeDriver::start();
  }

  private function getFirefoxDriver() : RemoteWebDriver {
    return RemoteWebDriver::create($this->serverUrl, DesiredCapabilities::firefox());
  }

  private function getEdgeDriver() : RemoteWebDriver {
    return RemoteWebDriver::create($this->serverUrl, DesiredCapabilities::microsoftEdge());
  }

  private function getSafariDriver() : RemoteWebDriver {
    return RemoteWebDriver::create($this->serverUrl, DesiredCapabilities::safari());
  }
}
