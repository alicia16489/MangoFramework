<?php

namespace core\components;

class RessourceMap
{
  public $ressources = array();

  public function __construct()
  {
    $this->ressources['physical'] = $this->getPhysical();
    $this->ressources['logic'] = $this->getLogic();
  }

  private function getPhysical()
  {
    //if(!is_readable("./ressources/physical/list.php"))

    if(!file_exists("./ressources/physical/list.php"))
      throw new Exception("Missing physical ressource list from ORM migrate command, path : "."./ressources/physical/list.php");

    return include("./ressources/physical/list.php");
  }

  private function getLogic()
  {

  }
}