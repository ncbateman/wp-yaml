<?php

namespace WpYaml;

use Symfony\Component\Yaml\Yaml as Yaml;
use Symfony\Component\Finder\Finder as Finder;

final class WpYaml
{

  private static $wp_yaml;

  public static function init()
  {
      if (! isset(self::$wp_yaml) ) {
          self::$wp_yaml = new self;
      }
      return self::$wp_yaml;
  }



}
