<?php 

namespace Automate\Scenario\Transformer;

class GoTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        //this is the regexp for http/https adress and http/https://localhost
        return ['go'=>':string :url'];
    }

    /**
     * {@inheritdoc}
     */
    protected function transform() : void
    {
        $this->driver->get($this->step['go']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Go at adresse %s', $this->step['go']);
    }
}