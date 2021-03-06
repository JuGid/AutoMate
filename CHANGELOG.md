# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

**These are technical changelogs.**

## [0.9.0] - 05/06/2021

### Added

- `Launcher` added to run tests with AutoMate.

## [0.8.0] - 01/04/2021

### Added

- `Logic` class to create some external business logic when you use AutoMate for repetitive tasks
- `LogicExecutor` to execute and get the answer of the logic (this is what the `LogicTansformer` use)
- `log` command to add message to log files
- `set` command to use variables

### Changed

- When dispatcher notify for `STEP_TRANSFORM` event, it now sends the logger.
- `condition` pattern. IfTrue, IfFalse => correct, incorrect
- Configuration for `logics` namespaces and valueAtException (false as default value). 
- `VariableRegistry` now precise in the error message that the variable can be not set.
- `print` now only print the text and not 'Text printed' after the print

### Fixed

Now working :
- `resize` 
- `textContains`
- `textIs`
- `textMatches`

## [0.7.0] - 25/03/2021

### Added

- Possibility to create pages to reuse any element you defined
- `PageHandler` to handle page elements
- `Page` command to load the page in pageHandler
- New `PrintListener` catch events to print what is needed
- Run AutoMate with a full option console command

### Changed

- Patterns are in harmony (syntax respects some standards)
- Verbose mode is now in three possibilities VERBOSE_ALL, VERBOSE_REPORT_ONLY, VERBOSE_NONE;

## [0.6.1] - 15/03/2021

### Changed

- `AutoMateError` stores the Exception class

## [0.6.0] - 15/03/2021

### Added

- New verbose mode true/false. Tell automate if Console could write text or not. If in test mode, the report is always printed.
- New event `AutoMateEvents::STEP_TRANSFORM_END`

### Changed 

- PrintListener is use to conditionnally use Console class

### Fixed

- AutoMate dispatch end events (`core:runner:simple:end`, `core:runner:spec:end`) as it should.

## [0.5.0] - 12/03/2021

### Added

- `Submit` command to fill then submit an element
- `Keyboard` command to simulate keyboard
- `Mouse` command to simulate mouse
- `Print` command to print a text in the console
- `Form` command to make actions on forms
- AutoMate now use an EventDispatcher that trigger some events [See here](https://github.com/JuGid/AutoMate/wiki/Events)
- Chromedriver can run in headless mode. _Geckodriver coming soon with php-webdriver update._
- New option _before_ to clear the content for `Fill` command
- Checkboxes and Radios support in `Select` command

### Changed 

- `Use` command does not detect loop anymore as a condition can make the scenario change
- You can register plugins on multiple events `AutoMate::registerPlugin(AutoMateListener $listener)` if your listener implements AutoMateListener::onEvent() and return an array.
- If you call a non existent command, `Runner` throw a `CommandException`, AutoMate returns en error in handler.
- Wiki is not empty anymore
- The active window is always the last opened window. When closing window, the webdriver switch to the **last** window using the `WindowHandler` previous windows queue.
- `Alert` new pattern to support sendKeys type

### Fixed 

- `Scenario` can now reset or not the scope. When using `use`, the Scenario variable scope is not reset.
- When AutoMate runs, the World variable scope is reset.
- `Frame` command can now return to default content
- `Use` command now use the good scenario.
- `textIs`, `textContains`, `textMatches` work now
- Fixed a bug that throw an error when the `GetTransformer` set the World variable
- The webdriver always switch to the new window so you can click and create without getting errors.

## [0.4.1] - 28/02/2021

### Fixed

- Scenario reset the variable scope each time you call the constructor

## [0.4.0] - 27/02/2021

### Added

- `Script` command to use javascript in your scenario (`script`)
- `Wajax` command to wait an ajax request (`wajax`)
- `Configuration` command to chage some configuration elements at run time (`configuration`)
- `ErrorHandler` to handle errors and render it on Console

### Changed

- Functions that wait() now use the configuration wait. `Configuration::get('wait.for')` and `Configuration::get('wait.every')` 
- If testMode is enable, the errors are rendered with colors and details (simple and specification scenarios)
- `Configuration` permit to test if it is loaded or not
- Remade `ErrorHandler` to handle errors from scenario with specification **and** simple scenario. Error rendering should be render by an ErrorPrinter and not the ErrorHandler itself. This should change in future. Error handler now handle every errors.

### Fixed

- Commands that use wait()->until() now have an error message to show what was wrong
- `Use` command detect if there is a potential loop
- `Configuration` throw Exception if config is null or not loaded

## [0.3.0] - 24/02/2021

### Changed

- Variables are stored in one Variable registry and not one for each scope.
- Variable scope `global` changed to `world` because global can be confusing
- `DriverManager` has a `DriverConfiguration` to access FirefoxProfile, Server Url and Http proxy
- `Runner` refactored
- `BrowserException` threw in `DriverManager::getDriver()` doesn't exist anymore since this is a real Configuration Exception
- `DriverManager` is now abstract. This class has only one job : get the driver and doesn't need to be instanciated.

### Fixed

- Code coverage is better now even if it's juste an indicator
- Useless imports deleted

## [0.2.0] - 23/02/2021

### Added

- `Use` command to import scenario `use: sub.name` or `use: main.name`
    - You can use `main.name` to run a scenario that is scenario_folder/name/main.yaml
    - You can use `sub.name` to run a scenario that is scenario_folder/current_scenario/name.yaml
- You can now use a proxy (see [example.php](example/example.php))
- New scenarios example

### Fixed

- You can now use AutoMate with Geckodriver (not only Chromedriver)

### Changed

- `Scenario` class can now open any scenario file. `AutoMate` still open the main.yaml first.

## [0.1.0] - 22/02/2021

### Initial release

- Run your scenario with our without specification
- You can use multiple browsers (Firefox, Chrome, Safari, ...)
- Configure the browser to use in your configuration file or in your scenario
- Use specification files to repeat the scenario with different data
- Logs are possible when you use a specification file
- Commands to use in your scenario (32) : (patterns and uses are described on the wiki)
    - `alert`
    - `click`
    - `condition`
    - `cookie`
    - `create` (will be update in future version)
    - `deselect`
    - `exit`
    - `fill`
    - `frame`
    - `get`
    - `go`
    - `impltm` (IMPLicit Time Out)
    - `isClickable`
    - `isNotSelected`
    - `isSelected`
    - `loop`
    - `numberOfWindows`
    - `presenceOfAll`
    - `presenceOf`
    - `reload`
    - `resize`
    - `screenshot`
    - `textContains`
    - `textIs`
    - `textMatches`
    - `titleContains`
    - `titleIs`
    - `titleMatches`
    - `urlContains`
    - `urlIs`
    - `urlMatches`
    - `visibilityOfAny`
    - `visibilityOf`
- Three variable scopes :
    - global : with `get` command and use it in your scenario
    - spec : from the specification file with column name
    - scenario : from your scenario

