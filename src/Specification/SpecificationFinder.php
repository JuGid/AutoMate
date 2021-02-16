<?php

namespace Automate\Specification;

use Automate\Exception\SpecificationException;
use Automate\Handler\GlobalVariableHandler;

class SpecificationFinder {

    /**
     * This function find a file that match the $scenario_name. '.yaml' pattern
     * But if you have a folder which has for name $scenario_name, you can use the
     * name you want. In this case, it looks for the first file without "_PROCESSED"
     * suffix.
     * It looks for this in folders (ordered) :
     * $spec_path/$scenario_name : first file without "_PROCESSED"
     * $spec_path : file which has for name $scenario_name and not "_PROCESSED"
     * 
     * @return Specification
     */
    public function find(string $spec_path) : Specification {

        $dirpath = $spec_path . '/'. GlobalVariableHandler::scenarioName();
        if(is_dir($dirpath)) {
            $files = array_diff(scandir($dirpath), ['..', '.']);
            foreach($files as $file) {
                if(!is_dir($dirpath . '/' . $file)) {
                    if(!$this->isProcessed($file) && $this->isCsv($dirpath. '/' . $file)) {
                        return new Specification($dirpath. '/' . $file);
                    }
                } 
            }
        }

        $files = array_diff(scandir($spec_path), ['..', '.']);
        foreach($files as $file) {
            if(!is_dir($spec_path . '/' . $file)) {
                if(!$this->isProcessed($file) && 
                   strpos($file, GlobalVariableHandler::scenarioName()) !== false &&
                   $this->isCsv($spec_path. '/' . $file)) {
                    return new Specification($spec_path . '/' . $file);
                }
            } 
        }

        throw new SpecificationException('The specification has not been found.');
    }

    public function isProcessed(string $file) : bool{
        return strpos($file, '_PROCESSED') !== false ;
    }

    public function isCsv(string $file) : bool {
        return pathinfo($file, PATHINFO_EXTENSION) == 'csv';
    }
}