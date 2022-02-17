<?php

// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace WpYaml;

use Symfony\Component\Yaml\Yaml as Yaml;
use Symfony\Component\Finder\Finder as Finder;

/**
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
final class WpYaml
{
    /** @psalm-suppress UndefinedConstant */
    public const CONFIG_PATH = WP_YAML_PATH . '/config/';

    private static $wp_yaml;

    private $config = [];

    private $resources = [];

    /*
    *  constuctor
    *
    *  Contructor, initialises local config setup and adds WordPress actions on
    *  'plugins_loaded' and on 'init' to set and process resources respectively.
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    private function __construct()
    {
        $this->init_config();
        add_action('setup_theme', [ $this, 'setup' ]);
        add_action('init', [ $this, 'process' ]);
    }

    /*
    *  init
    *
    *  Singleton one time initiation
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    public static function init()
    {
        if (! isset(self::$wp_yaml)) {
            self::$wp_yaml = new self();
        }
        return self::$wp_yaml;
    }

    public function resources()
    {
        return $this->resources;
    }

    public function config()
    {
        return $this->config;
    }

    /*
    *  init_config
    *
    *  Gets local config files, parses them and updates the private config member
    *  variable.
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function init_config()
    {
        $configs = $this->get_files($this::CONFIG_PATH);
        foreach ($configs as $config) {
            $config = Yaml::parseFile($config, Yaml::PARSE_CONSTANT);
            $this->config = array_merge($this->config, $config);
        }
    }

    /*
    *  register
    *
    *  Public register function for manual config directory registration.
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    public function register($path)
    {
        $this->resources['plugin_directories'][ $path ] = [];
    }

    /*
    *  set
    *
    *  Instantiates and prepares resource controllers for running on init.
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    public function setup()
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
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    private function get_configs()
    {
        if (isset($this->resources['plugin_directories']) && is_array($this->resources['plugin_directories'])) {
            foreach ($this->resources['plugin_directories'] as $path => &$configuration) {
                foreach ($this->config['definitions'] as $slug => $definition) {
                    $config_path = $path . $definition[1] . '/';
                    $configuration[ $slug ] = $this->get_files($config_path);
                }
            }
        }
    }

    /*
    *  load_controllers
    *
    *  Instantiates resource controllers with configuration
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function load_controllers()
    {
        if (isset($this->resources['plugin_directories']) && is_array($this->resources['plugin_directories'])) {
            foreach ($this->resources['plugin_directories'] as $path => &$configuration) {
                foreach ($configuration as $config_type => $config_files) {
                    foreach ($config_files as $config_file) {
                        $className = '\\WpYaml\\ResourceControllers\\' . $this->config['definitions'][ $config_type ][0];
                        $config = Yaml::parseFile($config_file);
                        $this->resources['controllers'][$path][]
                        =
                        new $className($config, $this->resources);
                    }
                }
            }
        }
    }

    /*
    *  set_controllers
    *
    *  Runs setup on each registered resource controller
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    private function set_controllers()
    {
        if (isset($this->resources['controllers']) && is_array($this->resources['controllers'])) {
            foreach ($this->resources['controllers'] as $controllers) {
                foreach ($controllers as $controller) {
                    $controller->setup();
                }
            }
        }
    }

    /*
    *  process
    *
    *  Runs process on each registered resource controller
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    public function process()
    {
        if (isset($this->resources['controllers']) && is_array($this->resources['controllers'])) {
            foreach ($this->resources['controllers'] as $controllers) {
                foreach ($controllers as $controller) {
                    $controller->process();
                }
            }
        }
    }

    /*
    *  get_files
    *
    *  Checks a directory for files and returns an array of paths.
    *  If no files exist, returns an empty array.
    *
    *  @type    function
    *  @date    21/12/18
    *  @since    0.0.0.1
    *
    *  @param    N/A
    *  @return    N/A
    */
    private function get_files($path)
    {
        if (! file_exists($path)) {
            return [];
        }
        $fileFinder = new Finder();
        $fileFinder->files()->in($path);
        $filesArray = [];
        foreach ($fileFinder as $file) {
            $filesArray[] = $file->getRealPath();
        }
        return $filesArray;
    }
}
