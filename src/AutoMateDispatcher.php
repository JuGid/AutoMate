<?php 

namespace Automate;

use Automate\Exception\EventException;

class AutoMateDispatcher {

    private $listeners = [];

    public function attach(string $event, AutoMateListener $object) {
        $this->listeners[$event][] = $object;
    }

    public function notify(string $event, $data) {
        if(!isset($this->listeners[$event])) {
            throw new EventException('No listeners subscribed to '. $event . ' event');
        }

        foreach($this->listeners[$event] as $listener) {
            $state = $listener->notify($event, $data);
        }
    }

    public function countListeners() : int {
        return count($this->listeners);
    }
}