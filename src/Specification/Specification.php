<?php 

namespace Automate\Specification;

use Iterator;

class Specification implements Iterator{
    /**
     * @var array|false
     */
    private $data;

    /**
     * @var int
     */
    private $datastep = 0;

    /**
     * @var SpecificationLoader
     */
    private $loader;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var int
     */
    private $rownumber;

    public function __construct(string $filepath) {
        $this->filepath = $filepath;
        $this->loader = new SpecificationLoader($filepath);
        $this->data = $this->loader->next();
        $this->rownumber = $this->countRows() - 1;
    }

    public function rewind() {
        $this->datastep = 0;
    }

    public function current() {
        return $this->data;
    }

    public function key() {
        return $this->datastep;
    }

    public function next() {
        $this->data = $this->loader->next();
        ++$this->datastep;
    }

    public function valid() {
        return $this->data !== false;
    }

    public function getFilepath() {
        return $this->filepath;
    }

    /**
     * @see https://stackoverflow.com/a/20537130
     */
    public function countRows() {
        $f = fopen($this->filepath, 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }

        fclose($f);

        return $lines;
    }

    /**
     * Rename the specification file to name_PROCESSED.
     * No more need since setProcessed must be called at the end of the run
     */
    public function setProcessed() {
        rename($this->filepath, $this->getPath() . '/' . $this->getFilename() . '_PROCESSED.' . $this->getExtension());
    }

    public function getPath() {
        return pathinfo($this->filepath)['dirname'];
    }

    public function getFilename() {
        return pathinfo($this->filepath)['filename'];
    }

    public function getExtension() {
        return pathinfo($this->filepath)['extension'];
    }

    public function getRowNumber() {
        return $this->rownumber;
    }

    public function getColumnsHeader() {
        return $this->loader->getColumnsHeader();
    }
}