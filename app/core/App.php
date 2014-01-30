<?php

namespace core;

use core\components\ressourceException;
use core\components\RouterException;
use Symfony\Component\ClassLoader\UniversalClassLoader;

class App
{
  public static $container;

  public static function run()
  {
    self::init();

      // IS HOME ? -- config home route ?!
      if (self::$container['Blueprints']->pathInfo != '/') {

        // DEFAULT STATE
        //self::$container['Router']->prepare('/error/405');

        // LOGIC
        if (self::$container['Blueprints']->exist['logic']) {

          if (self::$container['Blueprints']->isLogic()) {

            self::$container['Router']->logicRouting();
            self::$container['Blueprints']->type = "logic";
          } elseif (self::$container['Blueprints']->isSubLogic()) {

            echo "isSubLogic <br>";
            self::$container['Router']->subLogicRouting();
            self::$container['Blueprints']->type = "logic";
          }
        }
        // END LOGIC

        // PHYSICAL
        if (self::$container['Blueprints']->exist['physical'] && self::$container['Blueprints']->type != "logic") {

          if (self::$container['Blueprints']->isRest()) {
            self::$container['Router']->restRouting();
          }
        }
        // END PHYSICAL

      } else {
       // self::$container['Router']->prepare('/error/404');
      }

      if(self::$container['Blueprints']->exist['logic'] || self::$container['Blueprints']->exist['physical']){
        try
        {
          self::$container['Router']->execute();
        }
        catch(RouterException $e)
        {
          // no method
        }
      }

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

