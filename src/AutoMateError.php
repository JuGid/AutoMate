<?php

namespace Automate;

class AutoMateError
{
    private $type = '';

    private $dataset = [];

    private $exceptionClass = 'Exception';

    public function __construct(string $type, string $exceptionClass, array $dataset = [])
    {
        $this->type = $type;
        $this->exceptionClass = $exceptionClass;
        $this->dataset = $dataset;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getExceptionClass() : string
    {
        return $this->exceptionClass;
    }

    public function getDataset() : array
    {
        return $this->dataset;
    }

    public function setDataset(array $dataset) : void
    {
        $this->dataset = $dataset;
    }

    public function getDatasetAsString() : string
    {
        return implode(",", $this->dataset);
    }

    public static function compare(AutoMateError $a, AutoMateError $b)
    {
        return $a->getType() <=> $b->getType();
    }
}
