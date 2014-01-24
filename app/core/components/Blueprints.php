<?php

namespace core\components;

class Blueprints
{
  public $ressource;
  public $method;
  public $options;

  private $restPaterns = array(
    "index" => "#^\/[a-zA-Z]+\/?$#",
    "get" => "#^\/[a-zA-Z]+\/\d+$#",
    "post" => "#^\/[a-zA-Z]+\/?$#",
    "put" => "#^\/[a-zA-Z]+\/\d+$#",
    "delete" => "#^\/[a-zA-Z]+\/\d+$#"
  );

  public function __construct(Request $req)
  {
    var_dump($req);


  }

  private function isRessource()
  {

  }

  private function isRest()
  {

  }

  private function isComplexe()
  {

  }

  private function getOptions()
  {

  }
}