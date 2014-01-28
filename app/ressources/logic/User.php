<?php
namespace ressources\logic;
class User
{
  public $routes = array(
    "/user/toto/:id/name/:name" => array("method" => "myMethod","cond" => array( ":id" => "\d+")),
    //"/user/:id" => "myGet"
  );

/*  public function get()
  {
    echo "Ressource logic : User , method : get ";
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