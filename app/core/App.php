<?php

namespace core;

class App
{
  public static $container;

  public static function run()
  {
    self::init();

    if(self::$container['Blueprints']->ressource != '/'){
      if(self::$container['Blueprints']->isRessource()){
        echo "here";
      }
      else{
        echo "false";
        // exception
      }
    }
    else{
      // exception
    }
  }

  public static function init()
  {
    self::$container = Container::getInstance();
    self::$container->loaders();
  }

}

