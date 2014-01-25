<?php

namespace core\components;

class Blueprints extends \core\App
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
    $this->ressource = ucfirst($req->properties['REQUEST_OPTION_PARTS'][1]);
    var_dump($this);
  }

  public function isRessource()
  {
    $physicalList = self::$container['RessourceMap']->ressources['physical'];
    $logicList = self::$container['RessourceMap']->ressources['logic'];

    var_dump($physicalList);

    if(in_array($this->ressource, $physicalList) || in_array($this->ressource,$logicList))
      return true;

    return false;
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