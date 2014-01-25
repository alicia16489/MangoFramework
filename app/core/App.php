<?php

namespace core;
use Symfony\Component\ClassLoader\UniversalClassLoader;

class App
{
  public static $container;

  public static function run()
  {
    self::init();

    if(self::$container['Blueprints']->ressource != '/'){
      if(self::$container['Blueprints']->isRessource()){
        if(self::$container['Blueprints']->isRest()){
          var_dump(self::$container['Blueprints']);
        }
      }
      else{
        // error response
      }
    }
    else{
      // error response
    }
  }

  public static function init()
  {
    self::autoloader();
    self::$container = Container::getInstance();
    self::$container->loaders();

  }

  public static function autoloader()
  {
    require '../vendors/autoload.php';

    $loader = new UniversalClassLoader();
    $loader->useIncludePath(true);
    $loader->register();
  }

}

