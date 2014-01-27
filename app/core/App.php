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
    self::$container->loaders();



    var_dump(self::$container['Blueprints']->exist);
  }

}

