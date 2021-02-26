# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.4.0] - Coming

### Added

- `Script` command to use javascript in your scenario (`script`)
- `Wajax` command to wait an ajax request (`wajax`)
- `Configuration` command to chage some configuration elements at run time (`configuration`)
- `ErrorHandler` to handle errors and render it on Console

### Changed

- Functions that wait() now use the configuration wait. `Configuration::get('wait.for')` and `Configuration::get('wait.every')` 
- If testMode is enable, the errors are rendered with colors and details

### Fixed

- Commands that use wait()->until() now have an error message to show what was wrong

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

