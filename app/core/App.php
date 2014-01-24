<?php

namespace core;

class App
{
  public static $container;

  public static function run()
  {
    self::init();
  }

  public static function init()
  {
    self::$container = Container::getInstance();

    if(self::$container['Request']->properties['REQUEST_OPTION_PARTS'][1] != '/')
      self::$container['Blueprints']->isRessource();
  }

}

