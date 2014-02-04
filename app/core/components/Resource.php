<?php

namespace core\components;
use core\App;

abstract class Resource
{
  protected static $response;
  protected static $resource;

  public function beforeMain()
  {
    self::$resource = strtolower(str_replace('Resource','',str_replace('resources\physical\\','',get_called_class())));
    self::$response = App::$container['Response'];
  }
}