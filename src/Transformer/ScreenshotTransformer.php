<?php 

namespace Automate\Transformer;

use Automate\Transformer\Helpers\WebLocator;

class ScreenshotTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['screenshot' => 
                    [
                        'type'=> ':string :in("all","element")',
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
            $this->driver->takeScreenshot($array['sname']);
        } else {
            $element = $this->driver->findElement(WebLocator::get(array_keys($array)[2], array_values($array)[2]));
            $element->takeElementScreenshot($array['sname']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Cheeeeeese [%s]', $this->step['screenshot']['sname']);
    }

}