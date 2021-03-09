<?php

namespace Automate\Driver;

use Automate\Configuration\Configuration;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Automate\Exception\BrowserException;
use Automate\Exception\NotImplementedException;
use Automate\Handler\WindowHandler;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

/**
 * This class is used to get the good Webdriver and process the start of it.
 */
abstract class DriverManager
{

  /**
   * @codeCoverageIgnore
   *
   * @param string $browser The browser which you want the driver
   * @param DriverConfiguration $driverConfiguration The proxy
   * @return RemoteWebDriver|null
   */
    public static function getDriver(string $browser, DriverConfiguration $driverConfiguration = null)
    {
        $webdriverPath = Configuration::get('drivers.'.$browser.'.driver');

        if ($driverConfiguration == null) {
            $driverConfiguration = new DriverConfiguration();
        }
        $driver = null;
        $caps = null;

        if ($driverConfiguration->getHttpProxy() == null && 
            $browser == 'chrome' &&
            !$driverConfiguration->shouldRunHeadless()
           ) 
        {
            putenv('WEBDRIVER_CHROME_DRIVER=' . $webdriverPath);
            $driver = ChromeDriver::start();
        } else {
            $desired = new DesiredCapabilities();
      
            if (!method_exists($desired, $browser)) {
                throw new BrowserException('The browser ' . $browser . ' is not managed by php-webdriver');
            }

            $caps = DesiredCapabilities::{$browser}();

            //Set http proxy as a capability
            if ($driverConfiguration->getHttpProxy() !== null) {
                $caps->setCapability(
                    WebDriverCapabilityType::PROXY,
                    $driverConfiguration->getHttpProxy()->getAsCapability()
                );
            }

            //Set headless mode if needed
            if($driverConfiguration->shouldRunHeadless()) {
                switch($browser) {
                    case 'chrome':
                        $options = new ChromeOptions();
                        $options->addArguments(['--headless']);
                        $caps->setCapability(ChromeOptions::CAPABILITY_W3C, $options);
                        break;
                    default:
                        throw new NotImplementedException($browser. ' cannot run in headless mode');
                }
            }

            //Set firefox profile in browser capabilities
            if ($driverConfiguration->getFirefoxProfile() !== null && $browser == 'firefox') {
                $caps->setCapability(FirefoxDriver::PROFILE, $driverConfiguration->getFirefoxProfile());
            }
  
            $driver = RemoteWebDriver::create($driverConfiguration->getServerUrl(), $caps);
        }

        WindowHandler::setWindows($driver->getWindowHandles());
        return $driver;
    }
}
