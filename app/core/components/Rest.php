<?php

namespace core\components;
use core\App;
use models;

abstract class Rest extends Resource
{
  private static $class;

  public function beforeMain()
  {
    self::$class = 'models\\'.str_replace('Resource','',str_replace('resources\physical\\','',get_called_class()));
    parent::beforeMain();
  }

  private function getMethod($const)
  {
    $method = $const;
    $pos = strrpos($method,'::');
    $method = substr($method,$pos+2);
    return $method;
  }

  public function index()
  {
      $class = self::$class;
      $result = $class::All();
      $index = array();

      if(is_object($result)){

        foreach($result as $object)
        {
          $index[] = $object->getAttributes();
        }

        // set the response data default
        self::$response->setData($index,'default');
      }
  }

  public function get($id)
  {
    $class = self::$class;
    $result = $class::find($id);

    if(!is_object($result)){
      $data = array(
        'state' => 'Not Found',
        'resource' => self::$resource,
        'method' => self::getMethod(__METHOD__),
        'id' => $id
      );
    }
    else{
      $data = $result->getAttributes();
    }

    // set the response data default
    self::$response->setData($data,'default');
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