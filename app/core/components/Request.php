<?php

namespace core\components;

class Request
{
<<<<<<< HEAD

  public function __construct()
  {
=======
  private $request_method;
  private $request_uri;

  public function __construct()
  {
    $this->request_method = $_SERVER['REQUEST_METHOD'];
    var_dump($_SERVER);
>>>>>>> proto request class
  }
}