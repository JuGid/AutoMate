<?php

namespace Automate\Driver;

use Automate\Configuration\Configuration;
use Automate\Driver\Proxy\HttpProxy;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Exception\BrowserException;
use Automate\Exception\ConfigurationException;
use Automate\Handler\WindowHandler;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

/**
 * This class is used to get the good Webdriver and process the start of it.
 */
abstract class DriverManager {

  public function __construct(){}

  /**
   * @codeCoverageIgnore
   * 
   * @param string $browser The browser which you want the driver
   * @param DriverConfiguration $driverConfiguration The proxy
   * @return RemoteWebDriver|null 
   */
  public static function getDriver(string $browser, DriverConfiguration $driverConfiguration = null) {
    $webdriverPath = Configuration::get('drivers.'.$browser.'.driver');

    if($driverConfiguration == null) $driverConfiguration = new DriverConfiguration();
    $driver = null;
    $caps = null;

    if($driverConfiguration->getHttpProxy() == null && $browser == 'chrome') {
      putenv('WEBDRIVER_CHROME_DRIVER=' . $webdriverPath);
      $driver = ChromeDriver::start();
    } else {
      $desired = new DesiredCapabilities();
      
      if(!method_exists($desired, $browser)) {
        throw new BrowserException('The browser ' . $browser . ' is not managed by php-webdriver');
      }

      $caps = DesiredCapabilities::$browser();

      //Set http proxy as a capability
      if($driverConfiguration->getHttpProxy() !== null) {
        $caps->setCapability(
          WebDriverCapabilityType::PROXY, 
          $driverConfiguration->getHttpProxy()->getAsCapability()
        );
      }

      //Set firefox profile in browser capabilities
      if($driverConfiguration->getFirefoxProfile() !== null && $browser == 'firefox') {
        $caps->setCapability(FirefoxDriver::PROFILE,$driverConfiguration->getFirefoxProfile());
      }
  
      $driver = RemoteWebDriver::create($driverConfiguration->getServerUrl(), $caps);
    }

    WindowHandler::setWindows($driver->getWindowHandles());
    return $driver;
  }
}
