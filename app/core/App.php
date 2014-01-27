<?php

namespace core;

use Symfony\Component\ClassLoader\UniversalClassLoader;

class App
{
  public static $container;

  public static function run()
  {
    self::init();

    if (self::$container['Request']->properties['REQUEST_OPTION'] != '/') {

      if (self::$container['Blueprints']->exist['logic']) {

        if (self::$container['Blueprints']->isLogic()) {

          self::$container['Router']->logicRouting();
          self::$container['Blueprints']->type = "logic";
        } elseif (self::$container['Blueprints']->isSubLogic()) {

        }
      }

      if (self::$container['Blueprints']->exist['physical'] && self::$container['Blueprints']->type != "logic") {

        if (self::$container['Blueprints']->isRest()) {
          self::$container['Router']->restRouting();
        }
      }

      self::$container['Router']->execute();
    } else {
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

