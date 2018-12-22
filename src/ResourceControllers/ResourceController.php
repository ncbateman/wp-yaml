<?php

namespace WpYaml\ResourceControllers;

class ResourceController
{

    protected $config;

    function __construct( $config )
    {
        if (! isset($config) || empty($config) ) {
            die("wp-yaml fatal error - missing configuration");
        }
        $this->config = $config;
    }

    public function setup()
    {
        return true;
    }

    public function process()
    {
        return true;
    }

}
