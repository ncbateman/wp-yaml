<?php

namespace WpYaml\ResourceControllers;

class ResourceController
{

    private $config;

    function __construct( $config )
    {
        if (! isset($config) && empty($config) ) {
            die("wp-yaml fatal error - missing configuration");
        }
        $this->config = $config;
    }

    protected function setup()
    {
        return true;
    }

    protected function process()
    {
        return true;
    }

}
