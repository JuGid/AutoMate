<?php

namespace Automate;

class AutoMateError
{
    private $type = '';

    private $dataset = [];

    public function __construct(string $type, array $dataset = [])
    {
        $this->type = $type;
        $this->dataset = $dataset;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getDataset() : array
    {
        return $this->dataset;
    }

    public function setDataset(array $dataset) : void {
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
