<?php

namespace Automate\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Automate\Exception\ConfigurationException;
use Automate\Exception\BrowserException;

class Configuration implements ConfigurationInterface {

  /**
   * @var string
   */
  private $config_file;

  /**
   * @var string
   */
  private $scenarioFolder;

  /**
   * @var array
   */
  private $logs;

  /**
   * @var array
   */
  private $specs;

  /**
   * @var array
   */
  private $drivers;

  /**
   * @var string
   */
  private $default;

  public function __construct(string $config_file = __DIR__.'/../../config/config.yaml') {
    $this->setConfigurationFile($config_file);
    $this->load();
  }

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

  public function getConfigurationArray() : array {
    $config = Yaml::parse(file_get_contents($this->config_file));
    $processor = new Processor();
    return $processor->processConfiguration($this, $config);
  }

  public function setConfigurationFile(string $file) : void{
    if(file_exists($file) && pathinfo($file)['extension'] == 'yaml') {
      $this->config_file = $file;
      $this->load();
    }
    else {
      throw new ConfigurationException('The configuration file is not valid');
    }
  }

  public function isLogEnable() : bool{
    return $this->logs['enable'];
  }

  public function getLogsFolder() : string{
    return $this->logs['folder'];
  }
  
  public function getSpecFolder() : string{
    return $this->specs['folder'];
  }

  public function getScenarioFolder() : string{
    return $this->scenarioFolder;
  }

  public function setScenarioFolder(string $scenarioFolder) : void {
    $this->scenarioFolder = $scenarioFolder;
  }

  public function getDrivers() : array{
    return $this->drivers;
  }

  public function getDefaultBrowser() : string{
    return $this->default;
  }
  
  public function getWebdriverFolder(string $browser) : string{
    if(isset($this->drivers[$browser]['driver'])) {
      return $this->drivers[$browser]['driver'];
    }
    throw new BrowserException('The browser provided does not have a driver definition in configuration file');
  }

  private function load() : void
  {
      $config = $this->getConfigurationArray();
      $this->scenarioFolder=$config['scenario']['folder'];
      $this->logs=$config['logs'];
      $this->drivers=$config['drivers'];
      $this->default = $config['browser']['default'];
      $this->specs=$config['specs'];
  }

}
