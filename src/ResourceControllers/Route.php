<?php

namespace WpYaml\ResourceControllers;

class Route extends ResourceController
{
  
  public function setup()
  {
      foreach( $this->config['routes'] as $route_name => $route ) {
          $this->routes[ $route_name ] = new $route();
      }
  }

  public function process()
  {
      foreach( $this->routes as $route ){
          $route->register_routes();
      }
  }

}
