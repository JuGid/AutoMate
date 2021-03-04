<?php 

namespace Automate;

interface AutoMateListener {
    const STATE_RECEIVED = 0;
    const STATE_REJECTED = 1;
    
    public function notify(string $event, $data) : int ;
}