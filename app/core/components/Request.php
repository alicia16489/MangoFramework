<?php

namespace core\components;

class Request
{
  public $properties;
  public $headers;

  public function __construct()
  {
    var_dump($_SERVER);
    $env = array();

    $env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
    $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
    $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
    $env['REQUEST_OPTION'] = $this->extract($_SERVER['PHP_SELF'],$_SERVER['SCRIPT_NAME']);
    $env['REQUEST_OPTION'] = ($env['REQUEST_OPTION'] != $_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : $env['REQUEST_OPTION'];
    $env['REQUEST_OPTION_PARTS'] = explode("/",$env['REQUEST_OPTION']);

    var_dump($env);

    $this->properties = $env;
  }

  public function extract($phpSelf,$scriptName)
  {
    return str_replace($scriptName,'',$phpSelf);
  }
}