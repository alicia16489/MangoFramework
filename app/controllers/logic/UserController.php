<?php
namespace controllers\logic;
use core\components\Controller;

class UserController extends Controller
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
    echo "controller logic : User , method : get ";
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