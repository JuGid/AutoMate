![Tests](https://github.com/JuGid/AutoMate/workflows/Tests/badge.svg)
# AutoMate - Yaml automation library

## Presentation

AutoMate is a library which allows you to control web browsers with a normed YAML file, create scenario, and log the results.

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
	    	# One or more required
    		chrome:
    			driver : '/AutoMate/chromedriver'
    		firefox:
    			driver: '/AutoMate/gueckodriver'
    	scenario
			folder: '/AutoMate/scenario'
    	logs:
    		enable: false
    		folder: '/AutoMate/logs'


**NOTE :** Tests are automatically written in log files folder.

**🔥 Create your scenario yaml file**

    browser: chrome
    variables:
        name: 'bonjour'
        cookie: 'nouveau'
    scenario:
        steps:
    	   - go: 'https://youtube.com'
    	   - cookie:
    		    name: "{{ scenario.name }}"
    		    value: "{{ scenario.cookie }}"

**😄 Run your scenario**

***In app.php***

    <?php
    
    require  __DIR__.'/vendor/autoload.php';
    
    use Automate\AutoMate;
    
    $configFile = __DIR__.'/example/config/config-test.yaml'
    $automate = new AutoMate($configFile);
    
    /**
    * By default, the config file is in %this_lib_dir%/config/config.yaml
    * You can pass a new normed file (see doc) like this :
    * $scenarioRunner->setConfigurationFile(__DIR__.'/config/config.yaml');
    */
    $automate->run('youtube', true, true);

Don't forget to use `composer install` .

## 3️⃣ Future features

 - [ ] Loop and Conditions
 - [ ] Add more managed drivers
 - [ ] Add Proxy management

## 4️⃣ Contributing

We love to have your help to make AutoMate better. 
See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for more information about contributing and developing AutoMate

## 5️⃣ Simple example

You can find an example in ![example folder](example)


![AutoMate Screenshot](https://github.com/JuGid/AutoMate/blob/master/docs/images/screen_automate.png)



