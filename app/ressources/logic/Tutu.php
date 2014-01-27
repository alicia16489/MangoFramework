<?php

namespace ressources\logic;

class Tutu
{
  public $routes = array(
    "/tutu/toto/:id" => "myMethod"
  );

  public function get()
  {
    echo "get logic";
  }

  public function myMethod()
  {

  }
}