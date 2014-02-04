<?php

namespace core\components;
use core\App;
use models;

abstract class Rest extends Controller
{
  private static $class;

  private function getClass()
  {

  }

  public function beforeRest()
  {
    self::$class = 'models\\'.str_replace('Resource','',str_replace('resources\physical\\','',get_called_class()));
  }

  public function index()
  {
    if(class_exists(self::$class)){
      $class = self::$class;
      $result = $class::All();
      $index = array();

      if(is_object($result)){

        foreach($result as $object)
        {
          $index[] = $object->getAttributes();
        }

        var_dump($index);
      }
    }
  }

  public function get($id)
  {

  }

  public function post()
  {

  }

  public function put($id)
  {

  }

  public function delete ($id)
  {

  }
}