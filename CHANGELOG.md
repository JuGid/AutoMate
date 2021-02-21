# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 22/02/2021

# Initial release

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

