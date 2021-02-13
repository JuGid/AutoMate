<?php

namespace Automate\Driver;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Exception\BrowserException;
use Automate\Exception\DriverException;
use Automate\Handler\WindowHandler;

/**
 * This class is used to get the right Webdriver and process the start of it.
 */
class DriverManager {
  private $driver;
  private $webdriverFolder;

  public function __construct()
  {
    $this->driver = null;
    $this->webdriverFolder = '';
  }
  /**
   * @param string $browser The browser which you want the driver
   * @param string $webdriverFolder The webdriver folder
   * @return WebDriver The driver to use.
   */
  public function getDriver(string $browser, string $webdriverFolder) {
    $this->webdriverFolder = $webdriverFolder;
    
    if($browser == 'chrome') {
      $this->driver = $this->getChromeDriver();
    }elseif($browser == 'firefox') {
      $this->driver = $this->getFirefoxDriver();
    }elseif($browser == 'safari') {
      $this->driver = $this->getSafariDriver();
    }else {
      throw new BrowserException('The browser ' . $browser . 'is not managed by AutoMate');
    }
    WindowHandler::setWindows($this->driver->getWindowHandles());
    return $this->driver;
  }

  public function getCurrentDriver() {
    return $this->driver;
  }
  
  private function getChromeDriver() {
    putenv('WEBDRIVER_CHROME_DRIVER=' . $this->webdriverFolder);
    return ChromeDriver::start();
  }

  private function getFirefoxDriver() {
    throw new DriverException("Not implemented driver");
  }

  private function getSafariDriver() {
    throw new DriverException("Not implemented driver");
  }
}
