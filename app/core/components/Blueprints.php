<?php

namespace core\components;

class Blueprints extends \core\App
{
  private $request;
  public $ressource;
  public $type;
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
  }

  public function analyse()
  {
    $physicalList = self::$container['RessourceMap']->ressources['physical'];
    $logicList = self::$container['RessourceMap']->ressources['logic'];

    if(in_array($this->ressource,$logicList)){
      $this->isLogic();
    }

    if(in_array($this->ressource, $physicalList)  && $this->type != "physical"){
      $this->isRest();
    }

    return false;
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

  private function isLogic()
  {
    $class = '\ressources\logic\\'.$this->ressource;

    if(class_exists ($class))
      $ressource = new $class();
    else
      return;


  }
}