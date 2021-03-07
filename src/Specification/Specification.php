<?php 

namespace Automate\Specification;

use Iterator;

final class Specification implements Iterator{
    /**
     * @var array<string, mixed>|false
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
        $this->rownumber = $this->countRows();
    }

    /**
     * @return void
     */
    public function rewind() {
        $this->datastep = 0;
        $this->loader->reset();
        $this->data = $this->loader->next();
    }

    /**
     * @return array<string,string>
     */
    public function current() {
        return $this->data;
    }

    /**
     * @return int
     */
    public function key() {
        return $this->datastep;
    }

    /**
     * @return void
     */
    public function next() {
        $this->data = $this->loader->next();
        ++$this->datastep;
    }

    /**
     * @return bool
     */
    public function valid() {
        return $this->data !== false;
    }

    /**
     * @return string
     */
    public function getFilepath() {
        return $this->filepath;
    }

    /**
     * @return int
     * 
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
    public function setProcessed() : void {
        rename($this->filepath, $this->getPath() . '/' . $this->getFilename() . '_PROCESSED.' . $this->getExtension());
    }

    public function getPath() : string{
        return pathinfo($this->filepath)['dirname'];
    }

    public function getFilename() : string{
        return pathinfo($this->filepath)['filename'];
    }

    public function getExtension() : string{
        return pathinfo($this->filepath)['extension'];
    }

    public function getRowNumber() : int{
        return $this->rownumber;
    }

    /**
     * @return array<string>
     */
    public function getColumnsHeader() : array{
        return $this->loader->getColumnsHeader();
    }
}