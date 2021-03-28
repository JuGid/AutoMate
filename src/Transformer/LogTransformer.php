<?php

namespace Automate\Transformer;

class LogTransformer extends AbstractTransformer
{

    /**
     * {@inheritdoc}
     */
    protected function getPattern() : array
    {
        return ['log'=>':string'];
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    protected function transform() : void
    {
        $this->logger->addMessage($this->getStepData());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('Add message to log file : %s', $this->step['log']);
    }
}
