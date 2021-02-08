<?php 

namespace Automate\Scenario\Transformer\Helpers;

use Automate\Exception\NotAManagedCondition;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * WebDriverExpectedCondition overlay that defines the good conditions from type.
 * Warning : Not that much control on that class. Can catch them at a higher level.
 * @todo add control on values
 */
class WebConditions {

    public static function get(string $type, string $element = null, string $webLocatorType = null, string $text = null) {
        
        if($webLocatorType !== null && $element !== null) {
            $elmLocator = WebLocator::get($webLocatorType, $element);
        }
        
        switch($type) {
            case "isClickable":
                return WebDriverExpectedCondition::elementToBeClickable($elmLocator);
            case "isVisible":
                return WebDriverExpectedCondition::visibilityOfElementLocated($elmLocator);
            case "textIs":
                return WebDriverExpectedCondition::elementTextIs($elmLocator, $text);
            case "textContains":
                return WebDriverExpectedCondition::elementTextContains($elmLocator, $text);
            case "textMatches":
                return WebDriverExpectedCondition::elementTextMatches($elmLocator, $text);
            case "urlIs":
                return WebDriverExpectedCondition::urlIs($text);
            case "urlContains":
                return WebDriverExpectedCondition::urlContains($text);
            case "urlMatches":
                return WebDriverExpectedCondition::urlMatches($text);
            default:
                throw new NotAManagedCondition($type);
        }
    }
}