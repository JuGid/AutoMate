<?php 

namespace Automate\Specification;

use Automate\Exception\SpecificationException;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;

/**
 * To avoid memory problems, I prefer not store the data a second time in
 * its wholeness. So Automate open the file and then return lines when it's needed.
 * At the end, close the file.
 */
class SpecificationLoader {

    /**
     * Pointer to spec file
     * 
     * @var resource|false
     */
    private $file;

    /**
     * The columns from header
     * 
     * @var array<string>|false|null
     */
    private $columns;

    /**
     * @var int
     */
    private $columns_nb;

    /**
     * @var string
     */
    private $filepath;

    /**
     * The first line MUST be the header to define variables name
     */
    public function __construct(string $filepath) {
        $this->file = fopen($filepath, 'r');
        if($this->file === false) {
            throw new SpecificationException('Loader cannot open the specification file');
        }
        $this->filepath = $filepath;
        $this->columns = fgetcsv($this->file);
        $this->columns_nb = count($this->columns);
    }

    public function reset() {
        $this->file = fopen($this->filepath, 'r');
        $this->columns = fgetcsv($this->file);
    }

    /**
     * Give the next dataset from specification file
     * If the dataset is null, the line is empty, an Exception is thrown
     * If fgetcsv detect the EOL, next() return false and close file
     * 
     * @return array|bool
     */
    public function next() {
        $dataline = fgetcsv($this->file, 4096);

        if($dataline === null ) {
            throw new SpecificationException('There is an error in specification file. Could be an empty line');
        }

        if($dataline === false) {
            return $this->end();
        }

        $dataset = array_combine($this->columns, $dataline);
        VariableRegistry::reset(Scope::SPEC);
        VariableRegistry::setMultiple(Scope::SPEC, $dataset);
        
        return $dataset;
    }

    public function end() : bool {
        fclose($this->file);
        return false;
    }

    /**
     * @return array<string>
     */
    public function getColumnsHeader() : array{
        return $this->columns;
    }
}