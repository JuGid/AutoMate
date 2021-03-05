<?php 

namespace Automate;

use Automate\Exception\EventException;
use Automate\Handler\ErrorHandler;
use Automate\Mapper\ClassMapper;
use ReflectionClass;

final class AutoMateDispatcher {
    const CORE_LISTENERS =  [
        AutoMateEvents::STEP_TRANSFORM => [
            'directory'=> __DIR__.'/Transformer',
            'except'=> 'AbstractTransformer',
            'first'=> '',
            'last'=>'Transformer'
        ]
    ];

    private $listeners = [];

    public function __construct()
    {
        $this->attachCoreListeners();
    }

    public function attach(string $event, AutoMateListener $object) : void {
        $this->listeners[$event][] = $object;
    }

    public function notify(string $event, $data) : bool {
        $atLeastOneReceived = false;

        if(!isset($this->listeners[$event])) {
            return $atLeastOneReceived;
        }

        foreach($this->listeners[$event] as $listener) {
            $state = $listener->notify($event, $data);

            if($state == AutoMateListener::STATE_RECEIVED) $atLeastOneReceived = true;
        }

        return $atLeastOneReceived;
    }

    public function countListeners() : int {
        return count($this->listeners);
    }

    private function attachCoreListeners() : void {
        $mapper = new ClassMapper();

        foreach(self::CORE_LISTENERS as $event=>$params) {

            $map = $mapper->getClassMap(
                $params['directory'], $params['except'], $params['first'], $params['last']);

            $this->instanciateClassFromMapAndAttach($event, $map);
        }
        
    }

    private function instanciateClassFromMapAndAttach($event, $map) {
        foreach($map as $transformerClass) {
            $instance = (new ReflectionClass($transformerClass))->newInstance();

            if($instance instanceof AutoMateListener) {
                $this->attach($event, $instance);
            }
        }
    } 
}