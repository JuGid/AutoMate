<?php

namespace Automate\Transformer\Logic;

use InvalidArgumentException;

class MyLogicWithException extends Logic
{
    public function answeredBy() : bool
    {
        throw new InvalidArgumentException('Test exception throwing');
    }
}
