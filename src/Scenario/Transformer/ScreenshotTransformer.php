<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class ScreenshotTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['screenshot' => 
                    [
                        'type'=> ':string in("all","element")',
                        'sname'=>':string',
                        ':string :in("css","xpath","id","class","name","tag","linktext", "pltext") ?'=>':string'
                    ]
                ];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {   
        $array = $this->step['screenshot'];
        if($array['type'] == 'all') {
            $this->driver->takeScreenshot($array['name']);
        } else {
            $element = $this->driver->findElement(WebLocator::get(array_keys($array)[2], array_values($array)[2]));
            $element->takeElementScreenshot($array['name']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Cheeeeeese');
    }

}