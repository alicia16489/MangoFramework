<?php

namespace ressources\logic;

class Tutu
{
  public $routes = array(
    "/tutu/toto/:id/name/:name" => array("method" => "myMethod",
                                                                                "cond" => array(
                                                                                  ":id" => "\d+"
                                                                                ))
  );

  public function get()
  {
    echo "get logic";
  }

  public function myMethod()
  {

  }
}