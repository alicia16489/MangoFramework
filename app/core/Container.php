<?php

namespace core;
use core\components\Database;

class ContainerException extends \Exception{}

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
    $this['post'] = $_POST;
    $this['dependencies'] = array(
      'Config' => __NAMESPACE__.'\components\Config',
      'Request' => __NAMESPACE__.'\components\Request',
      'Blueprint' => __NAMESPACE__.'\components\Blueprint',
      'Router' => __NAMESPACE__.'\components\Router',
      'Response' => __NAMESPACE__.'\components\Response',
      'controllerMap' => __NAMESPACE__.'\components\controllerMap',
      'Database' => __NAMESPACE__.'\components\Database'
    );

    foreach($this['dependencies'] as $key => $path){
      if(!class_exists($path, true)){
        throw new ContainerException('Missing components : '.$key.' at path : '.$path);
      }
    }
  }

  public function loaders()
  {

    // Config
    $this['Config'] = function ($c) {
      return new $c['dependencies']['Config']();
    };

    // Request
    $this['Request'] = function ($c) {
      return new $c['dependencies']['Request']();
    };

    // Blueprint
    $this['Blueprint'] = function ($c) {
      return new $c['dependencies']['Blueprint']($c['Request']);
    };

    // Router
    $this['Router'] = function ($c) {
      return new $c['dependencies']['Router']($c['Blueprint']);
    };

    // Response
    $this['Response'] = function ($c) {
      return new $c['dependencies']['Response']();
    };

    // controllerMap
    $this['controllerMap'] = function ($c) {
      return new $c['dependencies']['controllerMap']();
    };

    // Database
    $this['Database'] = function (){
        return Database::getInstance();
    };
  }

}