<?php

/*
Plugin Name: Wp Yaml
Version:     1.1
Description: Yaml resource registration for WordPress
Author:      Nicholas Bateman
*/

namespace WpYaml;

define( 'WP_YAML_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_YAML_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( 'WpYaml\WpYaml' ) ) {
    if (is_file(WP_YAML_PATH . '/vendor/autoload.php')) {
        require_once WP_YAML_PATH . '/vendor/autoload.php';
    } elseif (is_file(WP_YAML_PATH . '../../../vendor/autoload.php')) {
        require_once WP_YAML_PATH . '../../../vendor/autoload.php';
    }
}

WpYaml::init();
