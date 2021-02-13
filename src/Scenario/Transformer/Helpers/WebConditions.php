<?php 

namespace Automate\Scenario\Transformer\Helpers;

use Automate\Exception\ConditionException;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * WebDriverExpectedCondition overlay that defines the good conditions from type.
 * Warning : Not that much control on that class. Can catch them at a higher level.
 * @deprecated This web condition will be removed in next commit
 */
class WebConditions {

    public static function get(string $type, string $element = null, string $webLocatorType = null, string $text = null) : WebDriverExpectedCondition{
        
        if($webLocatorType !== null && $element !== null) {
            $elmLocator = WebLocator::get($webLocatorType, $element);
        } else {
            $elmLocator = null;
        }

        /*
        PHP 8.0 Style with match
        return match($type) {
            "isClickable" => WebDriverExpectedCondition::elementToBeClickable($elmLocator),
            "isVisible"=> WebDriverExpectedCondition::visibilityOfElementLocated($elmLocator),
            "textIs"=> WebDriverExpectedCondition::elementTextIs($elmLocator, $text),
            "textContains"=> WebDriverExpectedCondition::elementTextContains($elmLocator, $text),
            "textMatches"=> WebDriverExpectedCondition::elementTextMatches($elmLocator, $text),
            "urlIs"=> WebDriverExpectedCondition::urlIs($text),
            "urlContains"=> WebDriverExpectedCondition::urlContains($text),
            "urlMatches"=> WebDriverExpectedCondition::urlMatches($text),
            default=> throw new ConditionException('THe condition ' . $type . ' does not exist'),
        }
        */
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
                throw new ConditionException('THe condition ' . $type . ' does not exist');
        }
    }
}