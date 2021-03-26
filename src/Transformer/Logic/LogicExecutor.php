<?php

namespace Automate\Transformer\Logic;

use Automate\AutoMateDispatcher;
use Automate\AutoMateEvents;
use Automate\Configuration\Configuration;
use Automate\Exception\LogicException;
use ReflectionClass;

class LogicExecutor
{
    private $answer;

    private $logicNamespaces = [];

    private $valueAtException = false;

    private $eventDispatcher;

    private $class = null;

    public function __construct()
    {
        $this->logicNamespaces = Configuration::get('logics.namespaces');
        $this->valueAtException = Configuration::get('logics.valueAtException');
    }

    public function setEventDispatcher(AutoMateDispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function getAnswser() : bool
    {
        if (!isset($this->answer)) {
            return $this->valueAtException;
        }

        return $this->answer;
    }

    /**
     * Return the right class from a specific namespace
     *
     * @param string $logicAccessor Should be class or namespace.class
     */
    public function for(string $logicAccessor) : self
    {
        $splitAccessor = explode(".", $logicAccessor);

        if (count($splitAccessor) == 1) {
            $this->class = $this->fromClassname($splitAccessor[0]);
        } elseif (count($splitAccessor) == 2) {
            $this->class = $this->fromNamespaceAndClassname($splitAccessor[0], $splitAccessor[1]);
        } else {
            throw new LogicException('Your logic accessor is wrong consider using namespace.classname or classname');
        }

        return $this;
    }

    private function fromClassname(string $classname) : ?string
    {
        foreach ($this->logicNamespaces as $namespace) {
            if (class_exists($namespace .'\\'.$classname)) {
                return $namespace.'\\'.$classname;
            }
        }

        return null;
    }

    private function fromNamespaceAndClassname(string $namespace, string $classname) : ?string
    {
        if (!isset($this->logicNamespaces[$namespace])) {
            return null;
        }

        if (!class_exists($this->logicNamespaces[$namespace] . '\\' . $classname)) {
            return null;
        }

        return $this->logicNamespaces[$namespace] . '\\' . $classname;
    }

    public function execute() : self
    {
        if (null === $this->class) {
            throw new LogicException('You should use for() method before execute()');
        }

        $logic = (new ReflectionClass($this->class))->newInstance();

        try {
            $this->answer = $logic->answeredBy();
        } catch (\Exception $e) {
            $this->eventDispatcher->notify(AutoMateEvents::LOGIC_EXCEPTION, ['exception' => $e]);
            $this->answer = $this->valueAtException;
        }
        
        return $this;
    }
}
