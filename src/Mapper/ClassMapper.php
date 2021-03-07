<?php 

namespace Automate\Mapper;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class ClassMapper {

    public function getClassMap(string $path, string $except = '', string $first = '', string $last = '') : array{
        $map = [];
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach($regex as $file) {

            if(!empty($except)) {
                if(strpos($file[0], $except) !== false ) continue;
            }

            if(!empty($first)) {
                if(!$this->matchFirst($file[0], $first)) continue;
            }

            if(!empty($last)) {
                if(!$this->matchLast($file[0], $last)) continue;
            }

            $className = $this->getClassNameFromFile($file[0]);
            $namespace = $this->getClassNamespaceFromFile($file[0]);

            if($className == null || $namespace == null) {
                continue;
            }

            $fullClassName = $namespace.'\\'.$className;
            
            $map[] = $fullClassName;
            
        }

        return $map;
    }

    private function matchFirst(string $name, string $first) : bool {
        return substr(pathinfo($name, PATHINFO_FILENAME), strlen($first)) == $first;
    }

    private function matchLast(string $name, string $last) : bool {
        return substr(pathinfo($name, PATHINFO_FILENAME), -strlen($last)) == $last;
    }

    /**
     * get the class namespace form file path using token
     *
     * @return  null|string
     */
    protected function getClassNamespaceFromFile(string $filePathName)
    {
        $src = file_get_contents($filePathName);

        $tokens = token_get_all($src);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (!$namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }

    /**
     * get the class name form file path using token
     *
     * @return  mixed
     */
    protected function getClassNameFromFile(string $filePathName)
    {
        $php_code = file_get_contents($filePathName);

        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING
            ) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }

        return $classes[0];
    }

    /*
    This function is not used anymore.
    
    public function getMap(string $path, string $except = '', string $first = '', string $last = '') : array {
        $map = [];
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach($regex as $file) {
            if(!empty($except)) {
                if(!strpos($except, $file[0])) continue;
            }

            if(!empty($first)) {
                if(!$this->matchFirst($file[0], $first)) continue;
            }

            if(!empty($last)) {
                if(!$this->matchLast($file[0], $last)) continue;
            }
            
            $className = $this->getClassNameFromFile($file[0]);
            $namespace = $this->getClassNamespaceFromFile($file[0]);

            if($className == null || $namespace == null) {
                continue;
            }

            $fullClassName = $namespace.'\\'.$className;
            
            $map[$file] = $fullClassName;
            
        }

        return $map;
    }
    */

}