<?php

namespace core\components;

class Blueprints extends \core\App
{
  private $request;
  public $ressource;
  public $type;
  public $exist = array();
  public $method;
  public $options;

  private $paterns = array(
    "rest" => array(
      "#^\/[a-zA-Z]+\/?$#",
      "#^\/[a-zA-Z]+\/\d+$#"
    ),
    "complexe" => array(

    )
  );

  public function __construct(Request $req)
  {
    $this->request = $req;
    $this->ressource = ucfirst($req->properties['REQUEST_OPTION_PARTS'][1]);
    $this->isLogic();
    $this->isPhysical();
  }

  private function isPhysical()
  {
    $physicalList = self::$container['RessourceMap']->ressources['physical'];

    var_dump($physicalList);
    echo $this->ressource;
    if(in_array($this->ressource, $physicalList)){
      $class = '\ressources\physical\\'.$this->ressource;

      if(class_exists ($class)){
        $this->exist['physical'] = true;
        return;
      }
    }
    $this->exist['physical'] = false;
  }

  private function isLogic()
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

  private function isRest()
  {
    foreach($this->paterns['rest'] as $method => $patern)
    {
      if(preg_match($patern,$this->request->properties['REQUEST_OPTION'])){
        $this->method = strtolower($this->request->properties['REQUEST_METHOD']);
        $this->type = "rest";
      }
    }
  }

  private function complexe()
  {

  }

  private function getOptions()
  {

  }
}