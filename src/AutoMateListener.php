<?php

namespace Automate;

interface AutoMateListener
{
    public const STATE_RECEIVED = 0;
    public const STATE_REJECTED = 1;
    
    /**
     * Return the events you want to subscribe
     * @return string|array
     */
    public function onEvent();
    
    /**
     * Define the action you want when the dispatcher
     * throw the event you subscribe
     */
    public function notify(string $event, $data) : int ;
}
