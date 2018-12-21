<?php

namespace WpYaml;

use Symfony\Component\Yaml\Yaml as Yaml;
use Symfony\Component\Finder\Finder as Finder;

final class WpYaml
{

  const CONFIG_PATH = WP_YAML_PATH . '/config/';

  private static $wp_yaml;

  private $config;

  /*
	*  constuctor
	*
	*  Contructor, initialises local config setup and adds WordPress actions on
  *  'plugins_loaded' and on 'init' to set and process resources respectively.
	*
	*  @type	function
	*  @date	21/12/18
	*  @since	0.0.0.1
	*
	*  @param	N/A
	*  @return	N/A
	*/
  private function __construct()
  {
      $this->init_config();
      add_action( 'plugins_loaded', [ $this, 'set' ] );
      add_action( 'init', [ $this, 'process' ] );
  }

  /*
	*  init
	*
	*  Singleton one time initiation
	*
	*  @type	function
	*  @date	21/12/18
	*  @since	0.0.0.1
	*
	*  @param	N/A
	*  @return	N/A
	*/
  public static function init()
  {
      if (! isset(self::$wp_yaml) ) {
          self::$wp_yaml = new self;
      }
      return self::$wp_yaml;
  }

  /*
	*  init_config
	*
	*  Gets local config files, parses them and updates the private config member
  *  variable.
	*
	*  @type	function
	*  @date	21/12/18
	*  @since	0.0.0.1
	*
	*  @param	N/A
	*  @return	N/A
	*/
  private function init_config () {
    $configs = $this->get_files($this::CONFIG_PATH);
    foreach( $configs as $config ) {
        $config = Yaml::parseFile($file, Yaml::PARSE_CONSTANT);
        $this->config = array_merge($this->config, $config);
    }
  }

  /*
  *  register
  *
  *  Public register function for manual config directory registration.
  *
  *  @type	function
  *  @date	21/12/18
  *  @since	0.0.0.1
  *
  *  @param	N/A
  *  @return	N/A
  */
  public function register( $path )
  {
      $this->resources['plugin_directories'][ $path ]['resources'] = [];
  }

  /*
  *  set
  *
  *  Instantiates and prepares resource controllers for running on init.
  *
  *  @type	function
  *  @date	21/12/18
  *  @since	0.0.0.1
  *
  *  @param	N/A
  *  @return	N/A
  */
  private function set ()
  {
    $this->get_configs();
    $this->load_controllers();
    $this->set_controllers();
  }

  /*
  *  get_configs
  *
  *  Checks registered plugin folders for defined resources and loads them into
  *  the resources private member variable.
  *
  *  @type	function
  *  @date	21/12/18
  *  @since	0.0.0.1
  *
  *  @param	N/A
  *  @return	N/A
  */
  private function get_configs()
  {
      foreach ( $this->resources['plugin_directories'] as $path => &$configuration ){
          foreach ( $this->config['definitions'] as $slug => $definition ){
              $config_path = $path . 'config/' . $definition[1] . '/';
              $configuration[ $slug ] = $this->get_files($config_path);
          }
      }
  }

  /*
  *  load_controllers
  *
  *  Instantiates resource controllers with configuration
  *
  *  @type	function
  *  @date	21/12/18
  *  @since	0.0.0.1
  *
  *  @param	N/A
  *  @return	N/A
  */
  private function load_controllers()
  {
      foreach ( $this->resources['plugin_directories'] as $path => &$configuration ){
          foreach ( $configuration as $config_type => $config_files ) {
              foreach ( $config_files as $config_file ) {
                  $className = '\\WpYaml\\ResourceControllers\\' . $this->config['definitions'][ $config_type ][0];
                  $config = Yaml::parseFile($config_file);
                  $this->resources['controllers'][$path][]
                  =
                  new $className( $config );
              }
          }
      }
  }

  /*
  *  set_controllers
  *
  *  Runs setup on each registered resource controller
  *
  *  @type	function
  *  @date	21/12/18
  *  @since	0.0.0.1
  *
  *  @param	N/A
  *  @return	N/A
  */
  private function set_controllers()
  {
      foreach ( $this->resources['controllers'] as $path => $controllers ){
          foreach( $controllers as $controller ) {
              $controller->setup();
          }
      }
  }

}
