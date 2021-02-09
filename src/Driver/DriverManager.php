<?php

namespace Automate\Driver;

use Automate\Exception\NotKnownBrowser;
use Automate\Exception\NoConfigurationFile;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Configuration\Configuration;

/**
 * This class is used to get the right Webdriver and process the start of it.
 */
class DriverManager {
  private $driver;
  private $webdriverFolder;

  /**
   * @param string $browser The browser which you want the driver
   * @param string $webdriverFolder The webdriver folder
   * @return WebDriver The driver to use.
   */
  public function getDriver(string $browser, string $webdriverFolder) {
    $this->webdriverFolder = $webdriverFolder;
    
    if($browser == 'chrome') {
      $this->getChromeDriver();
    }elseif($browser == 'firefox') {
      $this->getFirefoxDriver();
    }elseif($browser == 'safari') {
      $this->getSafariDriver();
    }else {
      throw new NotKnownBrowser();
    }
    return $this->driver;
  }

  private function getChromeDriver() {
    putenv('WEBDRIVER_CHROME_DRIVER=' . $this->webdriverFolder);
    $this->driver = ChromeDriver::start();
  }

  private function getFirefoxDriver() {
    throw new \Exception("Not implemented driver");
  }

  private function getSafariDriver() {
    throw new \Exception("Not implemented driver");
  }
}
