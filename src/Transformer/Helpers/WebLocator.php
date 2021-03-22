<?php

namespace Automate\Transformer\Helpers;

use Automate\Exception\CommandException;
use Automate\Handler\PageHandler;
use Facebook\WebDriver\WebDriverBy;

/**
 * WebDriverBy overlay to define the good one from string
 */
final class WebLocator
{

    /**
     * @return \Facebook\WebDriver\WebDriverBy
     */
    public static function get(string $type, string $element) : WebDriverBy
    {

        /*
        PHP 8.0 Style with match
        return match($type) {
            "css" => WebDriverBy::cssSelector($element),
            "xpath" => WebDriverBy::xpath($element),
            "id" => WebDriverBy::id($element),
            "class"=> WebDriverBy::className($element),
            "name" => WebDriverBy::name($element),
            "tag" => WebDriverBy::tagName($element),
            "linktext" => WebDriverBy::linkText($element),
            "pltext" => WebDriverBy::partialLinkText($element),
            default => throw new CommandException('Find an element with '. $type . ' is not allowed')
        }
        */
        switch ($type) {
            case "css":
                return WebDriverBy::cssSelector($element);
            case "xpath":
                return WebDriverBy::xpath($element);
            case "id":
                return WebDriverBy::id($element);
            case "class":
                return WebDriverBy::className($element);
            case "name":
                return WebDriverBy::name($element);
            case "tag":
                return WebDriverBy::tagName($element);
            case "linktext":
                return WebDriverBy::linkText($element);
            case "pltext":
                return WebDriverBy::partialLinkText($element);
            case "pageElement":
                $locators = PageHandler::get($element);
                return self::get(array_keys($locators)[0], array_values($locators)[0]);
            default:
                throw new CommandException('Find an element with '. $type . ' is not allowed');
        }
    }
}
