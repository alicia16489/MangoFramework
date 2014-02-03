<?php
namespace resources\logic;
class UserResource
{
  public $routes = array(
    "/user/toto/:id/name/:name" => array("method" => "myMethod","cond" => array( ":id" => "\d+")),
    "/user/myget" => "myGet"
  );

  public function before()
  {
    echo "BEFORE : logic User";
  }

  public function after()
  {
    echo "AFTER : logic User";
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
    echo "myGet !!!!";
  }

}