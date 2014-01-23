<?php

namespace core\components;

class Request
{
  private $properties;

  public function getRequestMethod()
  {
    return $this->requestMethod;
  }

  public function getRequestString()
  {
    return $this->requestString;
  }

  public function __construct()
  {
    var_dump($_SERVER);
    $env = array();

    $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
    $env['REQUEST_OPTION'] = $this->extract($_SERVER['PHP_SELF'],$_SERVER['SCRIPT_NAME']);
    $env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
    $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];

    $this->properties = $env;
  }

  public function extract($phpSelf,$scriptName)
  {
    return str_replace($scriptName,'',$phpSelf);
  }
}