<?php 

namespace Automate\Transformer;

use Automate\Configuration\Configuration;

class ConfigurationTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['configuration'=>
                    [
                        'wait?'=> [
                            'for' => ':string :regexp("/(\d)*/")',
                            'every'=> ':string :regexp("/(\d)*/")'
                        ]
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
        if(isset($this->step['configuration']['wait'])) {
            Configuration::$config_array['wait']['for'] = intval($this->step['configuration']['wait']['for']);
            Configuration::$config_array['wait']['every'] = intval($this->step['configuration']['wait']['every']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Configuration changed');
    }
}