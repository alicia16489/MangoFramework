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

    self::$container['Blueprints']->isRest();

    if (ob_get_length() > 0) {
        self::$container['Response']->write(ob_get_clean());
    }

    ob_start();
  }

  public static function stop($code = 200)
  {
    self::$container['Response']->status($code)
                                ->write(ob_get_clean())
                                ->send();
  }

}

