<?php

namespace core\components;

class Request
{
  public $properties;
  public $headers;

  public function __construct()
  {
    $env = array();

    $env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
    $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
    $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
    $env['REQUEST_OPTION'] = (empty($_SERVER['PATH_INFO']))? '/' : $_SERVER['PATH_INFO'];
    var_dump($env);
    $env['REQUEST_OPTION_PARTS'] = explode("/",$env['REQUEST_OPTION']);

    $this->properties = $env;
  }
}