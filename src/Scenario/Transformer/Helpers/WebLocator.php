<?php 

namespace Automate\Scenario\Transformer\Helpers;

use Facebook\WebDriver\WebDriverBy;

class WebLocator {

    public static function get(string $type, string $element) {
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
        }  
    }
}