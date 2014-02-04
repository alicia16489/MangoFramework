<?php

namespace core\components;
use core\App;

abstract class Resource
{
  protected static $response;

  public function beforeMain()
  {
    self::$response = App::$container['Response'];
  }
}