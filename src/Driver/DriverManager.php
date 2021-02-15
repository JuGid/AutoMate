<?php

namespace Automate\Driver;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Exception\BrowserException;
use Automate\Exception\ConfigurationException;
use Automate\Exception\DriverException;
use Automate\Handler\WindowHandler;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * This class is used to get the right Webdriver and process the start of it.
 */
class DriverManager {
  /**
   * @var RemoteWebDriver|null
   */
  private $driver;

  /**
   * @var string
   */
  private $webdriverFolder;

  public function __construct()
  {
    $this->driver = null;
    $this->webdriverFolder = '';
  }

  /**
   * @param string $browser The browser which you want the driver
   * @param string $webdriverFolder The webdriver folder
   * @return RemoteWebDriver|null 
   */
  public function getDriver(string $browser, string $webdriverFolder) {
    if(!empty($webdriverFolder)) {
      $this->webdriverFolder = $webdriverFolder;
    } else {
      throw new ConfigurationException('Webdriver folder is not valid');
    }
    
    if($browser == 'chrome') {
      $this->driver = $this->getChromeDriver();
    }elseif($browser == 'firefox') {
      $this->driver = $this->getFirefoxDriver();
    }elseif($browser == 'safari') {
      $this->driver = $this->getSafariDriver();
    }else {
      throw new BrowserException('The browser ' . $browser . ' is not managed by AutoMate');
    }
    WindowHandler::setWindows($this->driver->getWindowHandles());
    return $this->driver;
  }

  /**
   * @return RemoteWebDriver|null 
   */
  public function getCurrentDriver() {
    return $this->driver;
  }

  public function getWebdriverFolder() : string {
    return $this->webdriverFolder;
  }
  
  private function getChromeDriver() : ChromeDriver {
    putenv('WEBDRIVER_CHROME_DRIVER=' . $this->webdriverFolder);
    return ChromeDriver::start();
  }

  private function getFirefoxDriver() : RemoteWebDriver {
    throw new DriverException("Not implemented driver");
  }

  private function getSafariDriver() : RemoteWebDriver {
    throw new DriverException("Not implemented driver");
  }
}
