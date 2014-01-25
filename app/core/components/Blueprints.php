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
<<<<<<< HEAD
    $this->ressource = $req->properties['REQUEST_OPTION_PARTS'][1];
=======
    $this->ressource = ucfirst($req->properties['REQUEST_OPTION_PARTS'][1]);
    var_dump($this);
>>>>>>> blueprints
  }

  public function isRessource()
  {
<<<<<<< HEAD
<<<<<<< HEAD
    var_dump($this);
=======
    $physicalList = App::$container['RessourceMap']->ressources['physical'];
    $logicList = App::$container['RessourceMap']->ressources['logic'];
=======
    $physicalList = self::$container['RessourceMap']->ressources['physical'];
    $logicList = self::$container['RessourceMap']->ressources['logic'];
>>>>>>> blueprints


    if(in_array($this->ressource, $physicalList) || in_array($this->ressource,$logicList))
      return true;

    return false;
>>>>>>> blueprints
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