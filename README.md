![Tests](https://github.com/JuGid/AutoMate/workflows/Tests/badge.svg) [![codecov](https://codecov.io/gh/JuGid/AutoMate/branch/master/graph/badge.svg?token=JK9IA306US)](https://codecov.io/gh/JuGid/AutoMate) [![Latest Stable Version](https://poser.pugx.org/jugid/auto-mate/v)](//packagist.org/packages/jugid/auto-mate) [![Maintainability](https://api.codeclimate.com/v1/badges/d802b8dd267eea5eb801/maintainability)](https://codeclimate.com/github/JuGid/AutoMate/maintainability) [![License](https://poser.pugx.org/jugid/auto-mate/license)](//packagist.org/packages/jugid/auto-mate) ![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)


# AutoMate - Yaml automation library
*Release 0.7.0 see the [Changelogs](CHANGELOG.md)*

*You can get help on the [Wiki](https://github.com/JuGid/AutoMate/wiki)*

## Readme summary
- [Why AutoMate ?](https://github.com/JuGid/AutoMate#why-automate-)
- [Getting started](https://github.com/JuGid/AutoMate#getting-started)
- [Visual](https://github.com/JuGid/AutoMate#visual)
- [Support](https://github.com/JuGid/AutoMate#support)
- [Roadmap](https://github.com/JuGid/AutoMate#roadmap)
- [Contributing](https://github.com/JuGid/AutoMate#contributing)
- [License](https://github.com/JuGid/AutoMate#license)
- [Thanks](https://github.com/JuGid/AutoMate#thanks)

## Why AutoMate ?

At work, we need to make a lot of management acts. There is already a homemade framework which works well for this kind of actions. The thing is that it takes a long time to develop and create new scenarios. With AutoMate, I tried reducing this wasted time.

With AutoMate you can :
- Create multiple [scenarios](https://github.com/JuGid/AutoMate/wiki/Scenario) with multiple [commands](https://github.com/JuGid/AutoMate/wiki/Commands)
- Run them on different browsers
- [Configure](https://github.com/JuGid/AutoMate/wiki/Driver-configuration) it as you need
- Inject [data](https://github.com/JuGid/AutoMate/wiki/Specifications) into your scenario to use [variables](https://github.com/JuGid/AutoMate/wiki/Variables-scopes) in it and repeat the scenario for each dataset
- Get [logs](https://github.com/JuGid/AutoMate/wiki/Logs) from files to know which data was used when the scenario failed/successed
- Have a step by step description written on console
- Add your own commands and plugins thanks to the [event system](https://github.com/JuGid/AutoMate/wiki/Events)
- Use your own business logic using the [logics](https://github.com/JuGid/AutoMate/wiki/Logics)
- You can create test cases with [JuGid/AutoMate-tests](https://github.com/JuGid/AutoMate-tests) (under development)

## Getting started

### Installation 

**:arrow_right: Install AutoMate with composer**

```sh
composer require jugid/auto-mate
```

**:arrow_right: Get a Webdriver**

 - [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) : Chrome
 - [Geckodriver](https://github.com/mozilla/geckodriver/tree/v0.29.0) : Firefox
 - For Safari driver, see [Apple docs](https://developer.apple.com/documentation/webkit/testing_with_webdriver_in_safari)

**:arrow_right: Selenium Grid**

You can use Selenium Grid. See [Selenium Grid](https://www.selenium.dev/documentation/en/grid/) and [Selenium Grid 4 Downloads](https://www.selenium.dev/downloads/)

> If you do it, please send a PR with your example and a quick guide.

### Usage

To use AutoMate, you first need to :

1. Create your yaml configuration file
2. Create your scenario file with yaml
3. Create some folders corresponding to the scenario
4. Maybe create a specification csv file
5. Run AutoMate

**:arrow_right: Create your yaml configuration file**

Your configuration file should looks like [this](config/default-config.yaml). You MUST prefer using absolute path.

**:arrow_right: Create your scenario file with yaml**

A scenario is a list of steps that have to be executed by AutoMate. You also can declare variables and the browser to use for this specific scenario. You can find an example [here](example/scenario)

> Your scenario needs to be named `main.yaml` and saved in `scenario_folder/scenario_name/`

**:arrow_right: Create some folders corresponding to the scenario**

Now you have to create some folders corresponding to the scenario you just created.

* logs_folder/scenario_name/ : to log the results in details
* specs_folder/scenario_name/ : to load data in the scenario specification variables scope

**:arrow_right: Create your specification file**

Specification are csv files that provide data to AutoMate. It will execute the scenario for each lines and load data inside the spec variables scope.

Your specification files need to be saved in specs_folder/scenario_name/my_spec.csv.

> Your specification needs to have a header. Otherwise, your variables name will be the data at first line.

> You can create the number of specification you want. If AutoMate does not run in Test mode, the spec is renamed with the suffix `_PROCESSED` when the scenario run ends. The specification cannot be detected if it has `_PROCESSED` in its name.

**:arrow_right: Run AutoMate**

To run AutoMate, you can use the CLI launcher defined as :
_For the moment, the CLI is not 'completely'. Prefer the use of PHP_

```bash
php bin/automate run --scenario=scenario --config=/../config.yaml [--browser=NAME] [--headless] [--server=HTTP_ADRESSE] [--testMode] [--specMode]
```
or in a shorter way :

```bash 
php bin/automate run -s scenario -c /../config.yaml [-b NAME] [-h] [-a HTTP_ADRESSE] [-t] [-m]
```

You can also use the more php way as defined [in this example](https://github.com/JuGid/AutoMate/blob/master/example/example.php)

## Visual

This is what AutoMate looks like :

![AutoMate Screenshot](.github/images/screen_automate.png)

## Support

First, you can find help on the [Wiki](https://github.com/JuGid/AutoMate/wiki). Then if you don't find what you want, you can contact us.

## Roadmap
*See [Changelogs](CHANGELOG.md) for more information*

- [ ] Finish functionnal tests
- [x] Possibility to use personnal class for complex business logic
- [x] User can add message to log files
- [ ] **Tell us your ideas ! You can directly send a PR or open an issue**

### Will not be implemented

- [ ] Specific command to work with tables

## Contributing

We love to have your help to make AutoMate better. 
See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for more information about contributing and developing AutoMate

## License

AutoMate is under MIT License. You can find the license file at [License](LICENSE). 

## Thanks

 - [php-webdriver](https://github.com/php-webdriver/php-webdriver) for this amazing PHP binding solution
 - [PASVL](https://github.com/lezhnev74/pasvl) from Lezhnev74 for this array validation with patterns that help
 - [Symfony Config Component](https://github.com/symfony/config)
 - [Badge poser](https://poser.pugx.org) to provide an helper to pimp the README with great badges

 If you like AutoMate, do not hesitate to tell us how you love it and if you can, contribute.
