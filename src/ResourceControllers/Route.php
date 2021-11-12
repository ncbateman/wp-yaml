<?php

namespace WpYaml\ResourceControllers;

/**
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
class Route extends ResourceController
{
    private $routes;

    public function setup()
    {
        foreach ($this->config['routes'] as $route_name => $route) {
            $this->routes[ $route_name ] = new $route();
        }
    }

    public function process()
    {
        foreach ($this->routes as $route) {
            $route->register_routes();
        }
    }
}
