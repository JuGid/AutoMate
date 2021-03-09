<?php

namespace Automate;

interface AutoMateListener
{
    public const STATE_RECEIVED = 0;
    public const STATE_REJECTED = 1;
    
    public function notify(string $event, $data) : int ;
}
