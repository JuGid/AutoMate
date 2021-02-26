<?php

namespace Automate\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Automate\Exception\ConfigurationException;

class Configuration implements ConfigurationInterface {

  /**
   * @var bool
   */
  private static $isLoaded = false;

  /**
   * Array with all the configuration variables
   */
  public static $config_array = null;

  /**
   * Get a value of a config variable
   * 
   * Can be called as get('browser.default') or get('drivers.chrome.driver')
   * @return string|array|bool|int
   */
  public static function get(string $valueAsked) {
    if(!self::isLoaded()) {
      throw new ConfigurationException('The configuration is not loaded, please use Configuration::load');
    }

    if(self::$config_array === null) {
      throw new ConfigurationException('The configuration is empty. Did you use Configuration::load ?');
    }

    $gval = explode('.', $valueAsked);
    $arrayValue = self::$config_array;

    for($i = 0; $i< count($gval); $i++) {
      if(isset($arrayValue[$gval[$i]])) {
        $arrayValue = $arrayValue[$gval[$i]];
      } else {
        throw new ConfigurationException('The value asked '.$valueAsked. ' does not exist');
      }
    }
    return $arrayValue;

  }

  public static function logsColumns(array $columns = [])
  {
    if(!empty($columns))
    {
      self::$config_array['logs']['columns'] = $columns;
    }
  }

  public static function hasLogsExceptions() {
    return isset(self::$config_array['logs']['columns']);
  }

  /**
   * Load the config file as an array into $config_array
   * using symfony/config component
   * 
   * @see https://symfony.com/components/Config
   */
  public static function load(string $file) : void
  {
    if(file_exists($file) && pathinfo($file)['extension'] == 'yaml') {
      $config = Yaml::parse(file_get_contents($file));
      $processor = new Processor();
      self::$config_array = $processor->processConfiguration(new Configuration(), $config);
      self::$config_array['wait']['for'] = 30;
      self::$config_array['wait']['every'] = 250;
      self::$isLoaded = true;
      return;
    }

    throw new ConfigurationException('The configuration file is not valid or does not exist');
    
  }

  public static function reset() {
    self::$config_array = null;
    self::$isLoaded = false;
  }

  public static function isLoaded() : bool {
    return self::$isLoaded;
  }

  /**
   * Used by symfony/config
   */
  public function getConfigTreeBuilder() : TreeBuilder
  {
    $nodes = new TreeBuilder('automate');
    $nodes->getRootNode()
          ->children()
            ->arrayNode('browser')
              ->children()
                ->scalarNode('default')
                  ->isRequired()
                  ->cannotBeEmpty()
                ->end()
              ->end()
            ->end()
            ->arrayNode('drivers')
              ->useAttributeAsKey('name')
              ->arrayPrototype()
                ->children()
                  ->scalarNode('driver')
                    ->info('Where the browser WebDriver is stored.')
                    ->isRequired()
                  ->end()
                ->end()
              ->end()
            ->end()
            ->arrayNode('scenario')
              ->children()
                  ->scalarNode('folder')
                  ->isRequired()
                  ->cannotBeEmpty()
                ->end()
              ->end()
            ->end()
            ->arrayNode('specs')
              ->children()
                ->scalarNode('folder')->end()
              ->end()
            ->end()
            ->arrayNode('logs')
              ->children()
                ->booleanNode('enable')
                  ->defaultFalse()
                  ->isRequired()
                ->end()
                ->scalarNode('folder')
                  ->isRequired()
                  ->cannotBeEmpty()
                ->end()
              ->end()
            ->end()
          ->end();
    return $nodes;
  }

}
