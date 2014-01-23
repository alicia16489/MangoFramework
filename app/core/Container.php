<?php

namespace core;

class Container extends \Pimple
{
  private static $container;

  public static function getInstance()
  {
    if (is_null(self::$container)) {
      self::$container = new self();
    }

    return self::$container;
  }
  
  public function __construct()
  {
    $this['dependancies'] = array(
      'Config' => __NAMESPACE__.'\components\Config',
      'Request' => __NAMESPACE__.'\components\Request',
      'Blueprints' => __NAMESPACE__.'\components\Blueprints',
      'Router' => __NAMESPACE__.'\components\Router',
      'Response' => __NAMESPACE__.'\components\Response'
    );

    foreach($this['dependancies'] as $key => $path){
      if(!class_exists($path)){
        throw new \Exception('Missing components : '.$key.' at path : '.$path);
      }
    }

    $this->loaders();
    $this->load();
  }

  private function loaders()
  {
    // Config
    $this['Config'] = function ($c) {
      return new $c['dependancies']['Config']();
    };

    // Request
    $this['Request'] = function ($c) {
      return new $c['dependancies']['Request']();
    };

    // Blueprints
    $this['Blueprints'] = function ($c) {
      return new $c['dependancies']['Blueprints']();
    };

    // Router
    $this['Router'] = function ($c) {
      return new $c['dependancies']['Router']();
    };

    // Response
    $this['Response'] = function ($c) {
      return new $c['dependancies']['Response']();
    };
  }

  private function load()
  {
    foreach($this['dependancies'] as $key => $path)
    {
      $this[$key];
    }
  }

}