<?php 

namespace Automate\Scenario\Transformer;

use Automate\Driver\DriverManager;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class GoTransformer extends AbstractTransformer {

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['go'=>':string :url'];
    }

    /**
     * {@inheritdoc}
     * 
     * @codeCoverageIgnore
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