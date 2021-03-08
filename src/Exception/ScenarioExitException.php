<?php

namespace Automate\Exception;

class ScenarioExitException extends CustomException
{
    public function __construct(string $message = null, int $code = 0)
    {
        parent::__construct('The scenario exit with message : '.$message, $code);
    }
}
