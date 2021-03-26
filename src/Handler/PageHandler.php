<?php

namespace Automate\Handler;

use Automate\Configuration\Configuration;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

abstract class PageHandler
{
    private static $pageElements = [];

    private static string $pageName = '';
    /**
     * @param string $pageName Is the pageName in yaml format like wikipedia.index
     * where wikipedia is the folder and index the file
     */
    public static function load(string $pageName)
    {
        self::$pageName = $pageName;

        $pagesPath = Configuration::get('pages.folder');
        
        $pageNameSplit = explode(".", $pageName);

        foreach ($pageNameSplit as $path) {
            $pagesPath .= ('/' . $path);
        }

        self::$pageElements = Yaml::parse(file_get_contents($pagesPath . '.yaml'));
    }

    public static function get(string $element)
    {
        $elements = array_values(self::$pageElements)[0];
        
        if (!isset($elements[$element])) {
            throw new InvalidArgumentException('Element '.$element.' does not exist in page ' . self::$pageName);
        }

        return $elements[$element];
    }
}
