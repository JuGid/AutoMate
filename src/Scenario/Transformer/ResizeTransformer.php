<?php 

namespace Automate\Scenario\Transformer;

use Automate\Exception\CommandException;
use Facebook\WebDriver\WebDriverDimension;

/**
 * @codeCoverageIgnore
 */
class ResizeTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['resize'=>[
                            'type'=>':string :in("maximize","fullscreen","size")',
                            'width?'=>':number :int',
                            'height?'=>':number :int'
                        ]
                ];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {   
        $array = $this->step['resize'];
        if($array['type'] == 'maximize') {
            $this->driver->manage()->window()->maximize();
        }elseif($array['type'] == 'fullscreen') {
            $this->driver->manage()->window()->fullscreen();
        }elseif($array['type'] == 'size') {
            if(isset($array['width']) && isset($array['height'])) {
                $this->driver->manage()->window()->setSize(new WebDriverDimension(800, 600));
            }else {
                throw new CommandException('In resize command, if you use size. Tell width and height.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $array = $this->step['resize'];
        $str = isset($array['width']) && isset($array['height']) ? sprintf('with weight %s px and %spx',
                                                                            $array['width'],
                                                                            $array['height']
                                                                        ) : ' ';
        return sprintf('Resize the page [%s] %s', $this->step['resize']['type'], $str);
    }

}