<?php

namespace core\components;

class Request
{
  private $properties;

  public function __construct()
  {
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