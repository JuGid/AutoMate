<?php 

namespace Automate\Scenario\Transformer;

class GoTransformer extends AbstractTransformer {

    protected function getPattern()
    {
        //this is the regexp for http/https adress and http/https://localhost
        return ['go'=>':string :regexp("^(https?:\/\/)")'];
    }

    protected function transform() 
    {
        $this->driver->get($this->step['go']);
    }
}