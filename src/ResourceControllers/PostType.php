<?php

namespace WpYaml\ResourceControllers;

class PostType extends ResourceController
{

  public function process()
  {
      foreach ( $this->config as $post_type => $args ) {
          register_post_type($post_type, $args);
      }
  }

}
