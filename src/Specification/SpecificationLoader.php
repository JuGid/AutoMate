<?php 

namespace Automate\Specification;

use Automate\Exception\SpecificationException;
use Automate\Handler\SpecVariableHandler;

/**
 * To avoid memory problems, I prefer not store the data a second time in
 * its wholeness. So Automate open the file and then return lines when it's needed.
 * At the end, close the file.
 */
class SpecificationLoader {

    /**
     * @var ressource
     */
    private $file;

    /**
     * The columns from header
     * @var array
     */
    private $columns;

    /**
     * @var int
     */
    private $columns_nb;

    /**
     * The first line MUST be the header to define variables name
     */
    public function __construct(string $filepath) {
        $this->file = fopen($filepath, 'r');
        $this->columns = fgetcsv($this->file);
        $this->columns_nb = count($this->columns);
    }

    /**
     * Give the next dataset from specification file
     * If the dataset is null, the line is empty, an Exception is thrown
     * If fgetcsv detect the EOL, next() return false and close file
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
        SpecVariableHandler::load($dataset);
        
        return $dataset;
    }

    public function end() : bool {
        fclose($this->file);
        return false;
    }

    public function getColumnsHeader() {
        return $this->columns;
    }
}