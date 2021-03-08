<?php

namespace Automate;

use Automate\Exception\EventException;
use Automate\Mapper\ClassMapper;
use Automate\Transformer\AbstractTransformer;
use InvalidArgumentException;
use ReflectionClass;

final class AutoMateDispatcher
{
    const CORE_LISTENERS =  [
        AutoMateEvents::STEP_TRANSFORM => [
            'directory'=> __DIR__.'/Transformer',
            'except'=> 'AbstractTransformer',
            'first'=> '',
            'last'=>'Transformer'
        ]
    ];

    private $listeners = [];

    /**
     * @param array|string $event
     * @param AutoMateListener $object
     */
    public function attach($event, AutoMateListener $object) : void
    {
        if (is_array($event)) {
            array_map(function ($item) use ($object) {
                $this->attach($item, $object);
            }, $event);
        }

        if (!is_string($event)) {
            throw new InvalidArgumentException('Event should comes from class AutoMateEvents constants');
        }

        $this->listeners[$event][] = $object;
    }

    public function notify(string $event, $data) : bool
    {
        $atLeastOneReceived = false;

        if (!isset($this->listeners[$event])) {
            return $atLeastOneReceived;
        }

        foreach ($this->listeners[$event] as $listener) {
            $state = $listener->notify($event, $data);

            if ($state == AutoMateListener::STATE_RECEIVED) {
                $atLeastOneReceived = true;
            }
        }

        return $atLeastOneReceived;
    }

    public function countListeners() : int
    {
        return count($this->listeners, COUNT_RECURSIVE) - count($this->listeners, COUNT_NORMAL);
    }

    public function attachCoreListeners() : void
    {
        $mapper = new ClassMapper();

        foreach (self::CORE_LISTENERS as $event=>$params) {
            $map = $mapper->getClassMap(
                $params['directory'],
                $params['except'],
                $params['first'],
                $params['last']
            );

            $this->instanciateClassFromMapAndAttach($event, $map);
        }
    }

    private function instanciateClassFromMapAndAttach($event, $map)
    {
        foreach ($map as $transformerClass) {
            $instance = (new ReflectionClass($transformerClass))->newInstance();

            if (! ($instance instanceof AutoMateListener)) {
                throw new EventException('Listener should implements AutoMateListener interface');
            }

            if ($instance instanceof AbstractTransformer) {
                $instance->setDispatcher($this);
            }

            $this->attach($event, $instance);
        }
    }
}
