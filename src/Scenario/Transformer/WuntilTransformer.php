<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebConditions;
use Automate\Scenario\Transformer\Helpers\WebLocator;

class WuntilTransformer extends AbstractTransformer {

    protected function getPattern()
    {
        return [
            'wuntil' => [
                'for?' => ':number :int',
                'every?'=> ':number :int',
                ':string :regexp("(css)|(xpath)|(id)|(class)|(name)|(tag)|(linktext)|(pltext)") ?'=>':string',
                'condition' => ':string :regexp("(textIs)|(textContains)|(textMatches)|(isClickable)|(isVisible)|(urlIs)|(urlContains)|(urlMatches)")',
                'text?'=>':string'
                ]
            ];
    }

    protected function transform() 
    {
        $wuntilKeys = array_keys($this->step['wuntil']);
        $wuntilValues = array_values($this->step['wuntil']);
        
        $for = isset($this->step['wuntil']['for']) ? $this->step['wuntil']['for'] : 30;
        $every = isset($this->step['wuntil']['every']) ? $this->step['wuntil']['every'] : 250;
        $text = isset($this->step['wuntil']['text']) ? $this->step['wuntil']['text'] : null;
        
        $testId = 2;
        if(!isset($this->step['wuntil']['for']) && !isset($this->step['wuntil']['every']) ) {
            $testId = 0;
        } 

        if($wuntilKeys[0] == "condition") { //there is no element
            $webCondition = WebConditions::get($this->step['wuntil']["condition"], null, null, $text);
        } else { //there is an element
            $webCondition = WebConditions::get($this->step['wuntil']["condition"], $wuntilValues[$testId], $wuntilKeys[$testId], $text);
        }

        $this->driver->wait($for, $every)->until($webCondition);
    }

}