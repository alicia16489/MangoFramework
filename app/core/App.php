<?php

namespace core;

use Symfony\Component\ClassLoader\UniversalClassLoader;

class App
{
  public static $container;

  public static function run()
  {
    self::init();
    self::$container['Router']->errorRouting();

    if (self::$container['Blueprints']->pathInfo != '/') {

      self::$container['Router']->prepare('/error/405');

      if (self::$container['Blueprints']->exist['logic']) {

        if (self::$container['Blueprints']->isLogic()) {

          self::$container['Router']->logicRouting();
          self::$container['Blueprints']->type = "logic";
        }
        elseif (self::$container['Blueprints']->isSubLogic()) {

          self::$container['Router']->subLogicRouting();
          self::$container['Blueprints']->type = "logic";
        }
      }

      if (self::$container['Blueprints']->exist['physical'] && self::$container['Blueprints']->type != "logic") {

        if (self::$container['Blueprints']->isRest()) {
          self::$container['Router']->restRouting();
        }
      }

    } else {
      self::$container['Router']->prepare('/error/404');
    }

    self::$container['Router']->execute();

    // send response
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

