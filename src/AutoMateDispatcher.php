<?php 

namespace Automate;

use Automate\Exception\EventException;
use Automate\Mapper\ClassMapper;
use ReflectionClass;

class AutoMateDispatcher {

    private $listeners = [];

    public function __construct()
    {
        $this->attachCoreListeners();
    }

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

    private function attachCoreListeners() {
        $path = __DIR__.'/Scenario/Transformer';
        $mapper = new ClassMapper();
        $map = $mapper->getClassMap($path, 'AbstractTransformer', '', 'Transformer');

        foreach($map as $transformerClass) {
            $instance = (new ReflectionClass($transformerClass))->newInstance();

            if($instance instanceof AutoMateListener) {
                $this->attach(AutoMateEvents::STEP_TRANSFORM, $instance);
            }
        }
    }
}