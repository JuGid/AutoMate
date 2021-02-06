<?php

namespace Automate\Driver;

use Automate\Exception\NotKnownBrowser;
use Automate\Exception\NoConfigurationFile;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Configuration\Configuration;

class DriverManager {
  private $driver;
  private $configuration;

  public function getDriver(string $browser) {
    if($this->configuration == null) {
      throw new NoConfigurationFile();
    }

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
    putenv('WEBDRIVER_CHROME_DRIVER=' . $this->configuration->getWebdriverFolder('chrome'));
    $this->driver = ChromeDriver::start();
  }

  private function getFirefoxDriver() {
    throw new \Exception("Not implemented driver");
  }

  private function getSafariDriver() {
    throw new \Exception("Not implemented driver");
  }

  public function setConfiguration(Configuration $config) : void{
    $this->configuration = $config;
  }

}
