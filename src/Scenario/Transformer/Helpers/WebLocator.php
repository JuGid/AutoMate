<?php 

namespace Automate\Scenario\Transformer\Helpers;

use Automate\Exception\CommandException;
use Facebook\WebDriver\WebDriverBy;

/**
 * WebDriverBy overlay to define the good one from string
 */
class WebLocator {

    /**
     * @return \Facebook\WebDriver\WebDriverBy
     */
    public static function get(string $type, string $element) : WebDriverBy {
        switch($type) {
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
            default:
                throw new CommandException('Find an element with '. $type . ' is not allowed');
        }  
    }
}