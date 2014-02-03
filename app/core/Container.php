<?php

namespace core;

use core\components\Database;

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
    $this['dependencies'] = array(
      'Config' => __NAMESPACE__.'\components\Config',
      'Request' => __NAMESPACE__.'\components\Request',
      'Blueprints' => __NAMESPACE__.'\components\Blueprints',
      'Router' => __NAMESPACE__.'\components\Router',
      'Response' => __NAMESPACE__.'\components\Response',
      'RessourceMap' => __NAMESPACE__.'\components\RessourceMap',
      'Database' => __NAMESPACE__.'\components\Database',
      'Builder' => __NAMESPACE__.'\components\Builder'
    );

    foreach($this['dependencies'] as $key => $path){
      if(!class_exists($path, true)){
        throw new \Exception('Missing components : '.$key.' at path : '.$path);
      }
    }
  }

  public function loaders()
  {
    // Builder
    $this['Builder'] = function ($c) {
      return new $c['dependencies']['Builder']();
    };

    // Config
    $this['Config'] = function ($c) {
      return new $c['dependencies']['Config']();
    };

    // Request
    $this['Request'] = function ($c) {
      return new $c['dependencies']['Request']();
    };

    // Blueprints
    $this['Blueprints'] = function ($c) {
      return new $c['dependencies']['Blueprints']($c['Request']);
    };

    // Router
    $this['Router'] = function ($c) {
      return new $c['dependencies']['Router']($c['Blueprints']);
    };

    // Response
    $this['Response'] = function ($c) {
      return new $c['dependencies']['Response']();
    };

    // RessourceMap
    $this['RessourceMap'] = function ($c) {
      return new $c['dependencies']['RessourceMap']();
    };

    $this['Database'] = $this->share(function (){
        return Database::getInstance();
    });
  }

}