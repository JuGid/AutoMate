<?php 

namespace Automate\Scenario\Transformer;

use Automate\Scenario\Transformer\Helpers\WebLocator;

class ScreenshotTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     * @todo sendkeys and gettext to implement
     */
    protected function getPattern()
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
     */
    protected function transform() 
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
     * @todo change this message pls
     */
    public function __toString()
    {
        return 'Cheeeeeese';
    }

}