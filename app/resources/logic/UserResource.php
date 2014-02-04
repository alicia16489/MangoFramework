<?php
namespace resources\logic;
use core\components\Resource;

class UserResource extends Resource
{
  public $routes = array(
    "/user/toto/:id/name/:name" => array("method" => "myMethod","cond" => array( ":id" => "\d+")),
    "/user/myget" => "myGet"
  );

  public function before()
  {

  }

  public function after()
  {

  }

/*  public function get()
  {
    echo "resource logic : User , method : get ";
  }*/

  public function myMethod($id,$name)
  {
    echo "myMethod ! ".$id." ".$name;
  }

  public function myGet()
  {
    self::$response->setData('dfssd','default');
  }

}