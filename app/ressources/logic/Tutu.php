<?php

namespace ressources\logic;

class Tutu
{
  public $routes = array(
    "/tutu/toto/:id/name/:name" => array("method" => "myMethod",
                                                     "cond" => array(
                                                     ":id" => "\d+"
                                                     )),
    "/tutu/:id" => "myGet"
  );

  public function get()
  {
    echo "get logic";
  }

  public function myMethod()
  {
    echo "here";
  }

  public function myget()
  {
    echo "myGet";
  }
}