<?php

namespace WpYaml\ResourceControllers;

class ResourceController
{
    protected $config;

    protected $resources;

    /**
     * @param $config
     * @param $resources
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function __construct($config, $resources)
    {
        if (!isset($config) || empty($config)) {
            die("wp-yaml fatal error - missing configuration");
        }
        $this->config = $config;
        $this->resources = $resources;
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
