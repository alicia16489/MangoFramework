<?php

namespace core\components;

class Blueprints extends \core\App
{
  private $request;
  public $ressource;
  public $type;
  public $exist = array();
  public $restMethod;
  public $method;
  public $options;

  private $paterns = array(
    "rest" => array(
      "#^\/[a-zA-Z0-9_]+\/?$#",
      "#^\/[a-zA-Z0-9_]+\/\d+$#"
    ),
    "logic" => "#^\/[a-zA-Z0-9_]+\/?$#" ,
    "complexe" => array(

    )
  );

  public function __construct(Request $req)
  {
    $this->request = $req;
    $this->ressource = ucfirst($req->properties['REQUEST_OPTION_PARTS'][1]);
    $this->restMethod = $this->method = strtolower($this->request->properties['REQUEST_METHOD']);;
    $this->existAsLogic();
    $this->existAsPhysical();
  }

  private function existAsPhysical()
  {
    $physicalList = self::$container['RessourceMap']->ressources['physical'];

    if(in_array($this->ressource, $physicalList)){
      $class = '\ressources\physical\\'.$this->ressource;

      if(class_exists ($class)){
        $this->exist['physical'] = true;
        return;
      }
    }
    $this->exist['physical'] = false;
  }

  private function existAsLogic()
  {
    $logicList = self::$container['RessourceMap']->ressources['logic'];

    if(in_array($this->ressource,$logicList)){
      $class = '\ressources\logic\\'.$this->ressource;

      if(class_exists ($class)){
        $this->exist['logic'] = true;
        return;
      }
    }

      $this->exist['logic'] = false;
  }

  public function isLogic()
  {
    if(preg_match($this->paterns['logic'],$this->request->properties['REQUEST_OPTION']) && !$this->exist['physical'])
      return true;

    return false;
  }

  public function isSubLogic(){

  }

  public function isRest()
  {
    foreach($this->paterns['rest'] as $method => $patern)
    {
      if(preg_match($patern,$this->request->properties['REQUEST_OPTION'])){
        return true;
      }
    }

    return false;
  }

  private function complexe()
  {

  }

  private function getOptions()
  {

  }
}