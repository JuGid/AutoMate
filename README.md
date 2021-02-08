# AutoMate - Yaml automation library

## Presentation

AutoMate is a library which allows you to control web browsers with a normed YAML file.

This library uses [php-webdriver](https://github.com/php-webdriver/php-webdriver) and you can consider it as an overlay that provides you more easyness.

Thanks to 
 - [php-webdriver](https://github.com/php-webdriver/php-webdriver) for this amazing PHP binding solution
 - [PASVL](https://github.com/lezhnev74/pasvl) from Lezhnev74 for this array validation with patterns that help
 - [Symfony Config Component](https://github.com/symfony/config) for the component (obvious)

## 1️⃣ Installation (not yet)

**Installation is possible using [Composer](https://getcomposer.org/).**

If you don't already use Composer, you can download the `composer.phar` binary :

    curl -sS https://getcomposer.org/installer | php

Then install the library :

    php composer.phar require jugid/auto-mate

If you already have composer installed and in path :

    composer require jugid/auto-mate

## 2️⃣ Getting started

**🚘 Webdriver**

First of all, you need to download a driver to interact between AutoMate and your browser and control it. This driver will listen to the commands sent and execute them in the browser specified.

You can download multiple drivers but :

 - Be sure you have the driver for the browser you want to use in your scenario

**Download links**
*(Only chrome for the moment)*
 - [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) : Chrome

**🔧 Create your config.yaml file**

    automate:
    	browser:
    		default: chrome
    	drivers:
    		chrome:
    			driver : '/AutoMate/chromedriver'
    		firefox:
    			driver: '/AutoMate/gueckodriver'
    	scenarioFolder: '/AutoMate/scenario'
    	logs:
    		enable: false
    		folder: '/AutoMate/logs'
There are some required options :

    browser:
    	default: chrome
This option is required and cannot be empty. This option is used when you run a scenario and you didn't specify a browser.

     drivers:
    	 chrome:
    		 driver: '/AutoMate/chromedriver'
    	firefox:
    		driver: '/AutoMate/gueckodriver'
This option specify where the drivers are. You can set multiple element but one is required.

    scenarioFolder: '/AutoMate/scenario'
This is where the scenario files are stored. For the moment, AutoMate does not support multiple folders or recursive folders.

    logs:
    	enable: false
    	folder: '/AutoMate/logs'
You can log some element thanks to AutoMate. With this option you can specify if you enable logs or disable and where they have to be stored. 
**NOTE :** Tests are automatically written in log files.

**😄 Run your scenario**

***In app.php***

    <?php
    
    require  __DIR__.'/vendor/autoload.php';
    
    use Automate\Scenario\ScenarioRunner;
    
    $scenarioRunner = new  ScenarioRunner();
    
    /**
    * By default, the config file is in %this_lib_dir%/config/config.yaml
    * You can pass a new normed file (see doc) like this :
    * $scenarioRunner->setConfigurationFile(__DIR__.'/config/config.yaml');
    */
    $scenarioRunner->run('scenario', null);

Don't forget to use `composer install` .

## 3️⃣ Future features

 - [ ] Use spec files to pass data to scenario and run it multiple time
 - [ ] Add more control
 - [ ] Add more managed drivers
 - [ ] Add Proxy management

## 4️⃣ Contributing

We love to have your help to make AutoMate better. 
See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for more information about contributing and developing AutoMate
