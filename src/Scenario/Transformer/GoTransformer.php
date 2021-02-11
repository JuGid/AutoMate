<?php 

namespace Automate\Scenario\Transformer;

class GoTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern()
    {
        //this is the regexp for http/https adress and http/https://localhost
        return ['go'=>':string :url'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() 
    {
        $this->driver->get($this->step['go']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'GO at ' . $this->step['go'];
    }
}