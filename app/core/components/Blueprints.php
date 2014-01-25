<?php

namespace core\components;

class Blueprints
{
  private $request;
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
    $this->request = $req;
    $this->ressource = $req->properties['REQUEST_OPTION_PARTS'][1];
    var_dump($this);
  }

  public function isRessource()
  {
    var_dump($this->request);
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