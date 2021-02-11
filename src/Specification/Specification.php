<?php 

namespace Automate\Specification;

use Iterator;

class Specification implements Iterator{
    private $data;
    private $datastep;

    /**
     * @todo load data from csv file
     */
    public function __construct(string $filepath) {
        $this->datastep = 0;
        //data has to be a [$key => $value, $key => $value] format
    }

    public function rewind() {
        $this->datastep = 0;
    }

    public function current() {
        return $this->data[$this->datastep];
    }

    public function key() {
        return key($this->scenario[$this->datastep]);
    }

    public function next() {
        ++$this->datastep;
    }

    public function valid() {
        return isset($this->scenario[$this->datastep]);
    }
}