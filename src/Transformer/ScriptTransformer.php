<?php 

namespace Automate\Transformer;

class ScriptTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['script'=>':string'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        if(!substr(trim($this->step['script']), -1) == ';') {
            $this->step['script'] = trim($this->step['script']).';';
        }

        $this->driver->executeScript($this->step['script']);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Executing script %s', $this->step['script']);
    }
}